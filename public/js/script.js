// JavaScript básico para el proyecto MVC
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página cargada correctamente.');

    // Ejemplo: Agregar funcionalidad a los enlaces
    const links = document.querySelectorAll('nav a');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            // Aquí puedes agregar lógica, como confirmaciones o animaciones
            console.log('Navegando a: ' + this.href);
        });
    });
});