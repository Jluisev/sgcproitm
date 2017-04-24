var sgcproitm = sgcproitm || {};

sgcproitm.handleBreadCrumbs = function () {
    $("#actual").hide();
    $('#sidebar .sideNav li a').removeClass("active");
    $('#mainNav li a').removeClass("active");
    $("#linkini2").addClass("active");
};

sgcproitm.handleActiveLinks = function () {
    $('#sidebar .sideNav li a').removeClass("active");
    $('#mainNav li a').removeClass("active");
    $("#actual").html($(this).html());
    var a_href = $(this).attr('href');
    var a_target = $(this).attr('target');
    $("#actual").attr('href', a_href);
    $("#actual").attr('target', a_target);
    $("#actual").show();

    $(this).addClass("active");
};

$(document).ready(function () {
    $("#linkini, #linkini2").click(sgcproitm.handleBreadCrumbs);
    $("#link1, #link2, #link3, #link4, #link5, #link6, #link7, #link8, #link9").click(sgcproitm.handleActiveLinks);
});