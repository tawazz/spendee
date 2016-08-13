{% extends 'templates/default.php' %}

{% block content %}
<div class="row">
    <div class="col-sm-12">
        <h2>Budgets</h2>
    </div>
    <div class="col-sm-12">
        <button style="margin-bottom: 20px;" type="button" id="addItem" class="btn btn-info btn-raised" data-toggle="modal" data-target="#addBudget">
            <i class="fa fa-plus"></i> Add Budget
        </button>
    </div>
</div>
<div class="row">
  {% for budget in budgets %}
    <div class="col-xs-12 col-sm-6">
      <div class="panel panel-info">
          <div class="panel-heading">
              <h2>{{budget.name}}</h2>
          </div>
          <div class="alert alert-info">
            <div class="row">
              <div class="col-xs-6">
                <h4>Spent</h4>
                <p>${{budget.spent}}</p>
              </div>
              <div class="col-xs-6">
                <h4>Budgeted</h4>
                <p>${{budget.amount}}</p>
              </div>
            </div>
          </div>
          <!-- /.panel-heading -->
          <div class="panel-body" >
            {% if budget.spendingLeft >= 0 %}
            <p>
              You can keep spending
            </p>
            <p class="fa-2x">
              ${{budget.spendingLeft}}
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
              ${{budget.spent - budget.amount}}
            </p>
            <p>
              spend carefully!!!
            </p>
            <div class="progress progress-striped budget-progress">
              <div class="progress-bar progress-bar-danger" style="width:{{100}}%">{{budget.spentPercentage}}%</div>
            </div>
            {% endif %}

          </div>
          <!-- /.panel-body -->
          <div class="panel-footer">
            <h2 class="text-center">Budgeted Tags</h2>
              <div id="morris-pie-chart-tags-{{ budget.id }}">
                {% if not appData.exp_tags %} No Data Available {% endif %}
              </div>
          </div>
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
          <form name="addForm" id="addForm" method="post" action="{{ urlFor('budget.add') }}">
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Budget Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="amount" id="money" placeholder="Budgeted Amount">
            </div>
            <input type="hidden" class="form-control" name="date" value="{{appData.nav.current.year}}/{{appData.nav.current.month}}/1">
            <div class="form-group">
              <label for="tags">Budgeted For </label>
              <select class="form-control" name="tags[]" id="tags" multiple="multiple" style="width:100%;height:50px;">
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
  $("#tags").select2({
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
  </script>
{% endblock %}
