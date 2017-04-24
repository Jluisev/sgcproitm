var sgcproitm = sgcproitm || {};

sgcproitm.evaluarSolucion = {};

sgcproitm.evaluarSolucion.toggleEvaluacionManual = function() {
    if (this.checked) {
        $("#act").val("true");
        $("#inputs-evaluacion-manual").show();
    } else {
        $("#act").val("false");
        $("#inputs-evaluacion-manual").hide();
    }
};

$(document).ready(function () {
    $("#inputs-evaluacion-manual").hide();
    $("#grupoCorregir").click(sgcproitm.evaluarSolucion.toggleEvaluacionManual);
});