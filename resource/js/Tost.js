function Tost(text){
  var v_div = document.createElement('div')
  $(v_div).html(text)
  $(v_div).css({
    'position': 'fixed',
    'bottom': '60px',
    'background-color': '#000',
    'color': '#fff',
    'padding': '6px 8px',
    'border-radius': '3px',
    'font-size': '14px',
    'min-height': '14px'
  })

  // 居中
  var v_contentWidth = document.body.scrollWidth
  var v_tostX = (v_contentWidth - text.getWidth(14) - 16) / 2
  $(v_div).css('left', v_tostX + 'px')

  function _delObject(v_tmp){
    return function(){
      $(v_tmp).remove()
    }
  }
  setTimeout(_delObject(v_div),2000);
  $(document.body).append(v_div)
}
/* 计算文本宽度 */
String.prototype.getWidth = function(fontSize)
{
    var span = document.getElementById("__getwidth");
    if (span == null) {
        span = document.createElement("span");
        span.id = "__getwidth";
        document.body.appendChild(span);
        span.style.visibility = "hidden";
        span.style.whiteSpace = "nowrap";
        span.style.position = 'fixed';
    }
    span.innerText = this;
    span.style.fontSize = fontSize + "px";
    return span.offsetWidth;
}
