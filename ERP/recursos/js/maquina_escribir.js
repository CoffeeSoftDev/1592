var local = '';
var n = 0;
// $(function(){
//   setInterval(hola(), 300);
// });

function maquina(input,texto) {
  $('#'+input).removeAttr('placeholder');
  if ( $('#'+input).is(":focus") ) {
    $('#'+input).removeAttr('placeholder');
  }
  else {
    switch (n) {
      default:
      local = local + texto.charAt(n);
      n = n + 1;
      break;
      case 10:
      local = local + texto.charAt(n);
      local = '';
      n = 0;
      break;
    }
    $('#'+input).attr('placeholder',local);
  }
}
