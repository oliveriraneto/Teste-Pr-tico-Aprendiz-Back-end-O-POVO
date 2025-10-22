document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM Carregado - Iniciando JavaScript...');

    // Elementos da seção ADICIONAR
    const inputnome = document.getElementById('livro');
    const inputautor = document.getElementById('Autor');
    const buttonenviar = document.getElementById('enviar');

    // Elementos da seção BUSCAR 
    const inputprocurar = document.querySelector('.barra-buscar input');
    const buttonbuscar = document.querySelector('.barra-buscar button');

    // Elementos da seção RENOMEAR
    const inputrid = document.getElementById('rid');
    const inputrlivro = document.getElementById('rlivro');
    const inputrautor = document.getElementById('rautor');
    const buttontrocar = document.getElementById('trocar');

    // Elementos da seção REMOVER
    const inputexcluir = document.getElementById('excluir');
    const buttonremover = document.getElementById('remover');

    // Verificar se todos os elementos foram encontrados
    console.log('Elementos encontrados:');
    console.log('- Adicionar:', { inputnome, inputautor, buttonenviar });
    console.log('- Buscar:', { inputprocurar, buttonbuscar });
    console.log('- Renomear:', { inputrid, inputrlivro, inputrautor, buttontrocar });
    console.log('- Remover:', { inputexcluir, buttonremover });

    // Adicionar event listeners
    if (buttonenviar) {
        buttonenviar.addEventListener('click', adicionar);
        console.log('Event listener adicionado no botão Adicionar');
    }

    if (buttonbuscar) {
        buttonbuscar.addEventListener('click', buscar);
        console.log('Event listener adicionado no botão Buscar');
    }

    if (buttontrocar) {
        buttontrocar.addEventListener('click', trocar);
        console.log('Event listener adicionado no botão Renomear');
    }

    if (buttonremover) {
        buttonremover.addEventListener('click', remover);
        console.log('Event listener adicionado no botão Remover');
    }

    // URL da API PHP
    const API_URL = '../src/api.php';

    function adicionar() {
        console.log('Função adicionar chamada');
        const livro = inputnome ? inputnome.value.trim() : '';
        const autor = inputautor ? inputautor.value.trim() : '';

        console.log('Valores capturados:', { livro, autor });

        if (!livro || !autor) {
            alert('Por favor, preencha todos os campos!');
            return;
        }

        console.log('Enviando dados para API...');

        const formData = new FormData();
        formData.append('acao', 'adicionar');
        formData.append('livrodb', livro);
        formData.append('autor_livro', autor);

        fetch(API_URL, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text()) // pega o texto primeiro
        .then(text => {
            try {
                const data = JSON.parse(text); // tenta transformar em JSON
                console.log('Dados processados:', data);
                if (data.success) {
                    alert('Livro adicionado com sucesso! ID: ' + data.id);
                    if (inputnome) inputnome.value = '';
                    if (inputautor) inputautor.value = '';
                } else {
                    alert('Erro: ' + data.error);
                }
            } catch (err) {
                console.error('Erro ao processar JSON:', text);
                alert('Erro ao adicionar livro. Verifique o console.');
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
            alert('Erro ao adicionar livro. Verifique o console.');
        });
    }

    function buscar() {
        console.log('Função buscar chamada');
        const id = inputprocurar ? inputprocurar.value.trim() : '';

        console.log('ID para buscar:', id);

        if (!id) {
            alert('Por favor, digite um ID!');
            return;
        }

        const formData = new FormData();
        formData.append('acao', 'buscar');
        formData.append('id', id);

        fetch(API_URL, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                console.log('Resposta da busca:', data);
                if (data.success) {
                    alert(`Livro encontrado:\nID: ${data.livro.id}\nNome: ${data.livro.livrodb}\nAutor: ${data.livro.autor_livro}`);
                } else {
                    alert('Livro não encontrado ou erro: ' + data.error);
                }
            } catch (err) {
                console.error('Erro ao processar JSON:', text);
                alert('Erro ao buscar livro. Verifique o console.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao buscar livro');
        });
    }

    function trocar() {
        console.log('Função trocar chamada');
        const id = inputrid ? inputrid.value.trim() : '';
        const novoNome = inputrlivro ? inputrlivro.value.trim() : '';
        const novoAutor = inputrautor ? inputrautor.value.trim() : '';

        console.log('Valores para renomear:', { id, novoNome, novoAutor });

        if (!id || !novoNome || !novoAutor) {
            alert('Por favor, preencha todos os campos!');
            return;
        }

        const formData = new FormData();
        formData.append('acao', 'renomear');
        formData.append('id', id);
        formData.append('livrodb', novoNome);
        formData.append('autor_livro', novoAutor);

        fetch(API_URL, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                console.log('Resposta da renomeação:', data);
                if (data.success) {
                    alert('Livro atualizado com sucesso!');
                    if (inputrid) inputrid.value = '';
                    if (inputrlivro) inputrlivro.value = '';
                    if (inputrautor) inputrautor.value = '';
                } else {
                    alert('Erro: ' + data.error);
                }
            } catch (err) {
                console.error('Erro ao processar JSON:', text);
                alert('Erro ao atualizar livro. Verifique o console.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao atualizar livro');
        });
    }

    function remover() {
        console.log('Função remover chamada');
        const id = inputexcluir ? inputexcluir.value.trim() : '';

        console.log('ID para excluir:', id);

        if (!id) {
            alert('Por favor, digite um ID!');
            return;
        }

        const formData = new FormData();
        formData.append('acao', 'excluir');
        formData.append('id', id);

        fetch(API_URL, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                console.log('Resposta da exclusão:', data);
                if (data.success) {
                    alert('Livro excluído com sucesso!');
                    if (inputexcluir) inputexcluir.value = '';
                } else {
                    alert('Erro: ' + data.error);
                }
            } catch (err) {
                console.error('Erro ao processar JSON:', text);
                alert('Erro ao excluir livro. Verifique o console.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao excluir livro');
        });
    }

    console.log('JavaScript carregado com sucesso!');
});