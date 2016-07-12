<div id="confirmModal" class="modal fade">
    <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <!-- dialog body -->
        <div class="modal-body">
        <div class="row">
            <div id="modalIcon" class="col-sm-12 text-center" style="font-size:75px; ">
                <!--icon goes here-->
            </div>
            <div class="col-sm-12 text-center">
                <p id="modalText"><!--modal text--></p>
            </div>
        </div>
        </div>
        <!-- dialog buttons -->
        <div id="modalButtons" class="modal-footer">
            <!--buttons-->
        </div>
    </div>
    </div>
</div>
<script>
    /**
    * @params json
    * {
    *   icon :
    *   message :
    *   buttons:[
    *   {
    *       text:
    *       onClick:
    *       bsColor:
    *   }]
    * }
    */
    function comfirmBox(json)
    {
        var Obj = json;
        var comfirmModal = $("#confirmModal");
        var icon = $("#modalIcon");
        var text = $("#modalText");
        var buttons = ("#modalButtons");
        $(icon).html(Obj.icon);
        $(text).html(Obj.message);
        $(buttons).html("");

        if (typeof Obj.buttons != "undefined")
        {
            $.each(Obj.buttons, function (i, btn)
            {
                var eventHandler = (typeof btn.eventHandler != "undefined") ? btn.eventHandler : "onclick";
                $(buttons).append("<button type=\"button\" data-dismiss=\"modal\""+ eventHandler +"=" + btn.onClick + " class=\"btn " + btn.bsColor + "\">" + btn.text + "</button>");
            });
        }
        $(buttons).append("<button type=\"button\" data-dismiss=\"modal\" class=\"btn btn-default\">Cancel</button>");
        $(comfirmModal).modal('show');
    }
</script>
