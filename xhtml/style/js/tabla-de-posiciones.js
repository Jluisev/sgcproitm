var sgcproitm = sgcproitm || {};

sgcproitm.actualizarTabla = function () {

    $.ajax({
        url: '/sgcproitm-dev/sgcproitm/xhtml/controladores/actualizar-tabla.php'
    }).done(function (response) {
        $("#tabla-de-posiciones").find('tbody').html(response);
    });

};

sgcproitm.interval= function(func, wait, times){
    var interv = function(w, t){
        return function(){
            if(typeof t === "undefined" || t-- > 0){
                setTimeout(interv, w);
                try{
                    func.call(null);
                }
                catch(e){
                    t = 0;
                    throw e.toString();
                }
            }
        };
    }(wait, times);

    setTimeout(interv, wait);
};

$(document).ready(function () {
    sgcproitm.interval(sgcproitm.actualizarTabla, 5000);
});