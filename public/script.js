document.addEventListener('DOMContentLoaded', () => {
    const inputnome = DocumentByld('titulo');
    const inputautor = DocumentByld('autor');
    const buttonenviar = DocumentByld('enviar');

    const inputprocurar = DocumentByld('buscar');
    const buttonbuscar = DocumentByld('procura');

    const inputrid = DocumentByld('rid');
    const inputrlivro = DocumentByld('rlivro');
    const inputrautor = DocumentByld('rautor');
    const buttontrocar = DocumentByld('rtrocar');

    const inputexcluir = DocumentByld('excluir');
    const buttonremover = DocumentByld('remover');

    buttonenviar.addEventListener('click', adicionar);
    buttonbuscar.addEventListener('click', buscar);
    buttontrocar.addEventListener('click', trocar);
    buttonremover.addEventListener('click',remover); 
})