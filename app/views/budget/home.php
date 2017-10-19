{% extends 'templates/default.php' %}

{% block css %}
   <style >
      .empty-content{
         font-size: 28px;
         color:#ccc;
      }
   </style>
{% endblock %}

{% block content %}
<div class="row">
    <div class="col-sm-12">
        <h2>Budgets</h2>
    </div>
    <div class="col-sm-12">
      {% set currYear = "now"|date('Y') %}
      {% set currMonth = "now"|date('m') %}
      {% if appData.nav.current.year == currYear and appData.nav.current.month == currMonth %}
        <button style="margin-bottom: 20px;" type="button" id="addItem" class="btn btn-info btn-raised" data-toggle="modal" data-target="#addBudget">
            <i class="fa fa-plus"></i> Add Budget
        </button>
     {% endif %}
    </div>
</div>
<div class="row">
   {% if budgets|length < 1 %}
   <div class="col-xs-12">
     <div class="panel panel-info">
         <div class="panel-heading">
             <h2 class="text-center" style="text-transform:capitalize">No Budget Data Available</h2>
         </div>
         <div class="panel-body" style="height:300px;display: flex;justify-content: center; align-items: center;" >
             <h4 class="empty-content">Its Lonely Out here</h4>
         </div>
      </div>
   </div>
   {% endif %}
  {% for budget in budgets %}
    <div class="col-xs-12 col-sm-6">
      <div class="panel panel-info">
          <div class="panel-heading">
              <h2 style="text-transform:capitalize">{{budget.name}}</h2>
          </div>
          <div class="alert alert-info">
            <div class="row">
              <div class="col-xs-6">
                <h4>Spent</h4>
                <p>${{budget.spent|number_format(2, '.', ',')}}</p>
              </div>
              <div class="col-xs-6">
                <h4>Budgeted</h4>
                <p>${{budget.amount|number_format(2, '.', ',')}}</p>
              </div>
            </div>
          </div>
          <!-- /.panel-heading -->
          <div class="panel-body" >
            {% if budget.expired %}
                {% if budget.amount >= budget.spent  %}
                <p>
                  You Saved
                </p>
                <p class="fa-2x text-success">
                  ${{budget.saved|number_format(2, '.', ',')}}
                </p>
                <p>
                  this month!
                </p>
                <div class="progress progress-striped budget-progress">
                  <div class="progress-bar progress-bar-success" style="width:{{budget.spentPercentage}}%">{{budget.spentPercentage}}%</div>
                </div>
                {% else %}
                <p>
                  You over spent by
                </p>
                <p class="fa-2x text-danger">
                  ${{budget.saved|number_format(2, '.', ',')}}
                </p>
                <p>
                  this month!
                </p>
                <div class="progress progress-striped budget-progress">
                  <div class="progress-bar progress-bar-danger" style="width:{{100}}%">{{budget.spentPercentage}}%</div>
                </div>
                {% endif %}
            {% else %}
              {% if budget.spendingLeft >= 0 %}
              <p>
                You can keep spending
              </p>
              <p class="fa-2x text-success">
                ${{budget.spendingLeft|number_format(2, '.', ',')}}
              </p>
              <p>
                each day!
              </p>
              <div class="progress progress-striped budget-progress">
                <div class="progress-bar progress-bar-success" style="width:{{budget.spentPercentage}}%">{{budget.spentPercentage}}%</div>
              </div>
              {% else %}
              <p>
                Opps! you went over budget by
              </p>
              <p class="fa-2x text-danger">
                ${{(budget.spent - budget.amount)|number_format(2, '.', ',')}}
              </p>
              <p>
                spend carefully!!!
              </p>
              <div class="progress progress-striped budget-progress">
                <div class="progress-bar progress-bar-danger" style="width:{{100}}%">{{budget.spentPercentage}}%</div>
              </div>
              {% endif %}
            {% endif %}
          </div>
          <!-- /.panel-body -->
          <div class="panel-footer">
            <h2 class="text-center">Budgeted Tags</h2>
              <div id="morris-pie-chart-tags-{{ budget.id }}" class="text-center" {% if not appData.exp_tags %}style="padding:20% 0;"{% endif %}>
                {% if not appData.exp_tags %}
                <p>
                  No Data Available
                </p>
                {% endif %}
              </div>
          </div>
          {% if not budget.expired %}
          <div class="panel-footer">
              <a class="btn btn-info" href="#" data-budget-id ="{{ budget.id }}" data-show-modal="#editModal" data-toggle="modal" data-target="#editBudget" >Edit</a>
              <a class="btn btn-danger" href="#" data-budget-delete ="{{ budget.id }}">Delete</a>
          </div>
          {% endif %}
      </div>
      <!-- /.panel -->
    </div>
  {% endfor %}
</div>
<div class="modal fade" id="addBudget" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title text-capitalize">Add Budget </h4>
      </div>
      <div class="modal-body">
          <form name="addForm" id="addForm" method="post" action="{{ urlFor('budget.create') }}">
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Budget Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control money" name="amount" placeholder="Budgeted Amount">
            </div>
            <input type="hidden" class="form-control" name="date" value="{{appData.nav.current.year}}/{{appData.nav.current.month}}/1">
            <div class="form-group">
              <label for="tags">Budgeted For </label>
              <select class="form-control tags" name="tags[]" multiple="multiple" style="width:100%;height:50px;">
                <option value="0">All Tags</option>
                {% for tag in appData.tags %}
                <option value="{{tag.id}}">{{ tag.name }}</option>
                {% endfor %}
              </select>
            </div>
              <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}"/>
              <input type="hidden" name="user_id" value="{{auth.user_id}}"/>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" id="saveBudget" class="btn btn-primary" >Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="editBudget" tabindex="-2" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title text-capitalize">Edit Budget </h4>
      </div>
      <div class="modal-body">
          <form name="editForm" id="editForm" method="post" action="{{ urlFor('budget.update',{'id':'__URL__'}) }}">
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Budget Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control money" name="amount" placeholder="Budgeted Amount">
            </div>
            <input type="hidden" class="form-control" name="date" value="{{appData.nav.current.year}}/{{appData.nav.current.month}}/1">
            <div class="form-group">
              <label for="tags">Budgeted For </label>
              <select class="form-control tags" name="tags[]" id="editTags" multiple="multiple" style="width:100%;height:50px;">
                <option value="0">All Tags</option>
                {% for tag in appData.tags %}
                <option value="{{tag.id}}">{{ tag.name }}</option>
                {% endfor %}
              </select>
            </div>
                <input type="hidden" name="id" value="">
              <input type="hidden" name="{{csrf_key}}" value="{{csrf_token}}"/>
              <input type="hidden" name="user_id" value="{{auth.user_id}}"/>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" id="editBudgetBtn" class="btn btn-primary" >Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{% include 'parts/confirmbox.php'%}
{% endblock %}

{% block js %}
  <script type="text/javascript">
{% for budget in budgets %}
  Morris.Donut({
  element: 'morris-pie-chart-tags-{{ budget.id }}',
  data: [
      {% for tag,cost in budget.tags %}
          {label: "{{tag|raw}}", value:{{cost}} },
      {% endfor%}
  ],
  formatter:function (y, data) { return '$'+(y).formatMoney(2,'.',','); } ,
  colors:['#FF3D00'],
  resize:true
  });
  {% endfor %}

  $.fn.select2.defaults.set("theme", "classic");
  var selectTags = $(".tags").select2({
    tags: "true",
    placeholder: "Tags",
    allowClear: true,
    createTag: function (params) {
      // Don't offset to create a tag if there is no @ symbol
      if (params.term.indexOf('@') === -1) {
        // Return null to disable tag creation
        return null;
      }

      return {
        id: params.term,
        text: params.term
      }
  }
  });
$(document).ready(function(){

  $("#editBudgetBtn").on("click",function(event){
    var itmName = document.editForm.name.value;
    var amount = document.editForm.amount.value;
    if (itmName.length > 0  & parseFloat(amount) > 0.0) {
        $("#editForm").submit();
    } else {
      comfirmBox({
        icon:"<i class='fa fa-exclamation-triangle fa-2x text-warning' aria-hidden='true'></i>",
        message:"Fill in all fields",
      });
    }
  });

  $("a[data-show-modal]").on("click",function(event){
    event.preventDefault();
    var budgetId = $(this).attr('data-budget-id');
    var modal = $(this).attr('data-show-modal');
    var url = "{{ urlFor('budget.retrieve',{'id':'__URL__'}) }}";
    url = url.replace('__URL__',budgetId);
    $.ajax({
      method: "GET",
      url: url
    })
    .done(function( data ) {
      var obj = data;
      console.log(obj);
      document.editForm.name.value = obj.name;
      document.editForm.amount.value = obj.amount;
      document.editForm.id.value = obj.id;
      $("#editTags option").prop('selected', false);
      $.each( obj.tags, function( key, value ) {
        $("#editTags option[value='"+value.tag_id+"']").prop('selected', true);
        selectTags.trigger("change");
      });
    });
  });

  $("a[data-budget-delete]").on("click",function(event){
    event.preventDefault();
    var id = $(this).attr('data-budget-delete');
    comfirmBox({
      icon:"<i class='fa fa-exclamation-triangle fa-2x text-danger' aria-hidden='true'></i>",
      message:"Are you sure you want to Delete!!!",
      buttons:[
        {
          text:"Delete",
          onClick: "deleteBudget("+id+")",
          bsColor:"btn-danger",
        }
      ]
    });
  });

});

function deleteBudget(id){
  var url = "{{ urlFor('budget.delete',{'id':'__URL__'}) }}";
  url = url.replace('__URL__',id);
  $.ajax({
    method: "GET",
    url: url
  }).done(function( data ) {
    location.reload();
  });

}


  </script>
{% endblock %}
