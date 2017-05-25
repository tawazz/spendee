{% extends 'templates/default.php' %}

{% block content %}
<div class="row">
  <div class="col-sm-12 text-center text-capitalize">
      <h1>Income from {{name}} in {{appData.nav.display}}</h1>
  </div>
  <div class="col-sm-4">
    <div class="panel panel-success">
      <div class="panel-heading">
       <h3 class="panel-title text-center">Total Income </h3>
     </div>
      <div class="panel-body" {% if not inc %} style="min-height: 400px;display: flex;justify-content: center; align-items: center;" {% endif %}">
        <div id="morris-pie-chart-inc">
          {% if not inc %} No Data Available {% endif %}
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-8">
    <div class="panel panel-info">
        <div class="panel-heading">
          <div class="row">
              <div class="col-xs-12 text-center">
                  <span class="tx-2x">Monthly Incomes</span>
              </div>
          </div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div id="morris-bar-chart"></div>
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
  </div>
  <div class="col-sm-12">
    <div class="panel panel-success">
      <div class="panel-heading">
       <h3 class="panel-title text-center">Incomes From {{name}} </h3>
     </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-12">
            <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Income</th>
                  <th>Anount</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
              </thead>
              <tbody>
                {%for prod in products %}
                <tr>
                  <td>{{prod.date|date('d F Y')}}</td>
                  <td>{{prod.name}}</td>
                  <td>{{prod.cost}}</td>
                  <td id="{{prod.inc_id}}"><a style="color:#fff;" href="#" class="btn btn-info btn-raised" data-show-modal="#updateInc" data-inc-name="{{prod.name}}" data-inc-cost="{{prod.cost}}" data-inc-date="{{prod.date|date('Y/m/d')}}" data-inc-id="{{prod.inc_id}}" >Edit</a></td>
                  <td><a style="color:#fff;" href="#" class="btn btn-danger btn-raised" data-inc-delete="#deleteModal" data-inc-id="{{prod.inc_id}}" >Delete</a></td>
                </tr>
                {%endfor%}
              </tbody>
            </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="updateInc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title text-capitalize">Edit Income </h4>
      </div>
      <div class="modal-body">
          <form name="addForm" id="addForm" method="post" action="/income/update">
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Enter Item Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control money" name="cost" placeholder="Enter Amount">
            </div>
            <div class="form-group">
                <input type="text" class="form-control datepicker" name="date" placeholder="Date" data-provide="datepicker">
            </div>
              <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}"/>
              <input type="hidden" name="user_id" value="{{auth.user_id}}"/>
              <input type="hidden" name="inc_id" />
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" id="save" class="btn btn-primary" >Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="row" style="margin-top:35px">
        <div class="col-xs-12 text-center">
          <span class="glyphicon glyphicon-warning-sign text-danger fa-4x"></span>
        </div>
      </div>

        <h3 class="text-capitalize text-center">Are You Sure You want to Delete!!!</h3>
        <div class="modal-footer">
          <form name="deleteForm" action="/income/delete" method="post">
            <input type="hidden" name="inc_id">
            <input type="hidden" name="name" value="{{products.0.name}}">
            <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}"/>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" >Delete</button>
          </form>
        </div>
    </div>
  </div>
</div>
{% include 'parts/confirmbox.php'%}
{% endblock %}
{% block js %}
  <script type="text/javascript">
  {% if inc %}
  Morris.Donut({
  element: 'morris-pie-chart-inc',
  data: [
    {label: "{{name|raw}}", value:{{inc}} },
  ],
  formatter:function (y, data) { return '$'+(y).formatMoney(2,'.',','); } ,
  colors:["#00E676"],
  resize:true
  });
{% endif %}
    Morris.Bar({
    element: 'morris-bar-chart',
    data: [
      {%for key,val in monthly_inc%}
          { d: '{{(appData.nav.display ~'-'~key~'-1')|date('M')}}', a: {{val}} },
      {%endfor%}
    ],
    xkey: 'd',
    ykeys: ['a'],
    labels: ['Incomes'],
    barColors: ['#00E676'],
    preUnits:'$',
    resize:true
    });

    $("a[data-show-modal]").on("click",function(event){
      event.preventDefault();
      document.addForm.name.value = $(this).attr('data-inc-name');
      document.addForm.cost.value = $(this).attr('data-inc-cost');
      document.addForm.date.value = $(this).attr('data-inc-date');
      document.addForm.inc_id.value = $(this).attr('data-inc-id');
      $($(this).attr('data-show-modal')).modal('show');
    });
    $("a[data-inc-delete]").on("click",function(event){
      event.preventDefault();
      var exp = $(this).attr('data-inc-id');
      document.deleteForm.inc_id.value = exp;
      $($(this).attr('data-inc-delete')).modal('show');
    });
</script>
{% endblock %}
