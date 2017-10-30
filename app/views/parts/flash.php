{% if flash.getMessage('global') %}
<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
        <div class="alert">
            <div class="alert alert-warning alert-dismissible text-center" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong >{{ flash.getMessage('global') | first }}</strong>
            </div>
        </div>
    </div>
</div>
{% endif %}
