window.sr = ScrollReveal({ reset: true});

sr.reveal('.area-1', { origin: "top", distance: "1rem", duration:2000, reset: false});
sr.reveal('.area-2', { origin: "top", distance: "1rem", duration:2000, reset: false });
sr.reveal('.area-3', { origin: "top", distance: "1rem", duration:2000, reset: false });
sr.reveal('.area-9', { origin: "left", distance: "2rem", duration:2000, reset: false });
sr.reveal('.area-10', { origin: "left", distance: "0.5rem", duration:2000, reset: false });
sr.reveal('.area-12', { origin: "bottom", distance: "1rem", duration:2000, reset: false });

String.prototype.reverse = function(){
    return this.split('').reverse().join(''); 
};

function mascaraMoeda(campo,evento){
var tecla = (!evento) ? window.event.keyCode : evento.which;
var valor  =  campo.value.replace(/[^\d]+/gi,'').reverse();
var resultado  = ""; 
var mascara = "##.###.###,##".reverse();
for (var x=0, y=0; x<mascara.length && y<valor.length;) {
    if (mascara.charAt(x) != '#') {
    resultado += mascara.charAt(x);
    x++;
    } else {
    resultado += valor.charAt(y);
    y++;
    x++;
    }
}
campo.value = resultado.reverse();
}
