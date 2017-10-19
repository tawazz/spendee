<div class="row">
    <div class="col-xs-12">
      <div class="btn-group" role="group" aria-label="...">
        <a href="/{{page}}/{{appData.nav.prev}}" class="btn"><img src="/images/left.png"/></a>
        <span class="btn text-default" style="margin-top:7px;text-transform: capitalize;">{{appData.nav.display}}</span>
        <a href="/{{page}}/{{appData.nav.next}}" class="btn"><img src="/images/right.png"/></a>
      </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
      <div class="col-sm-4">
          <div class="panel panel-danger">
              <div class="panel-heading">
                  <div class="row">
                      <div class="col-xs-12">
                          <i class="fa fa-usd fa-2x">{{appData.exp_total|number_format(2,'.',',')}}</i>
                      </div>
                  </div>
              </div>
              <a href="{{urlFor('expenses',{year:appData.nav.current.year,month:appData.nav.current.month})}}">
                  <div class="panel-footer">
                      <span class="pull-left">View Expenses</span>
                      <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                      <div class="clearfix"></div>
                  </div>
              </a>
          </div>
      </div>
      <div class="col-sm-4">
          <div class="panel panel-success">
              <div class="panel-heading">
                  <div class="row">
                      <div class="col-xs-12">
                          <i class="fa fa-usd fa-2x">{{appData.inc_total|number_format(2,'.',',')}}</i>
                      </div>
                  </div>
              </div>
              <a href="{{urlFor('incomes',{year:appData.nav.current.year,month:appData.nav.current.month})}}">
                  <div class="panel-footer">
                      <span class="pull-left">View Incomes</span>
                      <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                      <div class="clearfix"></div>
                  </div>
              </a>
          </div>
      </div>
      <div class="col-sm-4">
          <div class="panel panel-default">
              <div class="panel-heading">
                  <div class="row">
                      <div class="col-xs-12">
                        {% if appData.balance >= 0 %}
                          <i class="fa fa-usd fa-2x">{{appData.balance|number_format(2,'.',',')}}</i>
                        {% else %}
                          <i class="fa fa-2x">-<i class="fa fa-usd">{{(appData.balance*-1)|number_format(2,'.',',')}}</i></i>
                        {% endif %}
                      </div>
                  </div>
              </div>
              <a href="{{urlFor('overview',{year:appData.nav.current.year})}}">
                  <div class="panel-footer">
                      <span class="pull-left">Balance</span>
                      <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                      <div class="clearfix"></div>
                  </div>
              </a>
          </div>
      </div>
    </div>
</div>
