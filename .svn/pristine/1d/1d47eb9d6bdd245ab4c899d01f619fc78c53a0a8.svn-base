<?php

define("JS_BUILDER_DIVISION", "\n");

/**
 * 绑定一个下拉框到另一个下拉框
 * @param 帮到到哪个元素ID上 $pToId
 * @param 要绑定的元素ID $pBuildId（根据$pToId的选择改变）
 * @param 数据  $pData
 */
function buildSelectToOther($pToId, $pBuildId, $pData, $pShowOrHideCall = "")
{
    $pCode = "$(document).ready(function() {".JS_BUILDER_DIVISION;
    $pCode .= "(function(){".JS_BUILDER_DIVISION;
    $pCode .= "var buildToSelect = document.getElementById(\"$pToId\");".JS_BUILDER_DIVISION;
    $pCode .= "var buildFromSelect = document.getElementById(\"$pBuildId\");".JS_BUILDER_DIVISION;
    $pCode .= "buildToSelect.onchange = function() {".JS_BUILDER_DIVISION;
    $pCode .= "var allMap=".json_encode($pData).";".JS_BUILDER_DIVISION;
    $pCode .= "var subMap=allMap[this.value];".JS_BUILDER_DIVISION;
    $pCode .= "if (subMap) {".JS_BUILDER_DIVISION;
    if ( ! empty($pShowOrHideCall) ) {
        $pCode .= "$pShowOrHideCall(true);".JS_BUILDER_DIVISION;
    }

    $pCode .= "buildFromSelect.options.length=0;".JS_BUILDER_DIVISION;
    $pCode .= "var i = 0;".JS_BUILDER_DIVISION;
    $pCode .= "for (var key in subMap) {".JS_BUILDER_DIVISION;
    $pCode .= "buildFromSelect.options[i] = new Option(subMap[key], key);".JS_BUILDER_DIVISION;
    $pCode .= "i++;".JS_BUILDER_DIVISION;
    $pCode .= "}".JS_BUILDER_DIVISION;
    $pCode .= "buildFromSelect.options[0].selected = true;".JS_BUILDER_DIVISION;
    $pCode .= "if(buildFromSelect.onchange){buildFromSelect.onchange();}".JS_BUILDER_DIVISION;
    $pCode .= "} else {".JS_BUILDER_DIVISION;
    if ( ! empty($pShowOrHideCall) ) {
        $pCode .= "$pShowOrHideCall(false);".JS_BUILDER_DIVISION;
    }

    $pCode .= "};".JS_BUILDER_DIVISION;
    $pCode .= "}".JS_BUILDER_DIVISION;
    $pCode .= "buildToSelect.onchange();".JS_BUILDER_DIVISION;
    $pCode .= "})();".JS_BUILDER_DIVISION;
    $pCode .= "});";

    return $pCode;
}

/**
 * 绑定一个下拉框到另一个下拉框
 * @param 帮到到哪个元素ID上 $pToId
 * @param 要绑定的元素ID $pBuildId  （根据$pToId的选择改变）
 * @param 数据  $pData
 */
function makeLendonSelect($pToId, $pBuildId, $pData, $pShowOrHideCall = "")
{
    $pAllMap = json_encode($pData);
    $pShowFunc = empty($pShowOrHideCall) ? "" : $pShowOrHideCall."(true);";
    $pHideFunc = empty($pShowOrHideCall) ? "" : $pShowOrHideCall."(false);";
    $pCode = <<<EOF
        $(function() {
          (function(){
            var buildToSelect   = $("#$pToId");
            var buildFromSelect = $("#$pBuildId");
            buildToSelect.change(function() {
                var val = buildToSelect.val();
                var allMap = $pAllMap;
                var map = allMap[val];
                buildFromSelect.html("");
                if (map) {
                    $.each(map, function(key, value) {
                        buildFromSelect.append("<option value='" + key +"'>" + value + "</option>")
                    });
                    buildFromSelect.change();
                    $pShowFunc
                } else {
                    $pHideFunc
                }
            });
            buildToSelect.change();
          })();
        });
EOF;

    return $pCode;
}