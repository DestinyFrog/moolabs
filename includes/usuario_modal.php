
<dialog id="modal-editar-usuario">
    <article>
        <header>
            <button aria-label="Close" rel="prev" onclick="toggle_modal_usuario()"></button>
            <h3>Editar Perfil</h3>
        </header>

        <form method="post" action="/crud/edita_usuario.php"
            enctype="multipart/form-data">
            <fieldset>
                <input type="hidden" name="id" value="<?= $user['id'] ?>">

                <label >
                    Nome completo
                    <input type="text" id="usuario-nome" name="nome"
                        placeholder="Seu nome completo" required value="<?= $user['nome'] ?>">
                </label>

                <label >
                    E-mail
                    <input type="email" id="usuario-email" name="email"
                        placeholder="seu@email.com" required value="<?= $user['email'] ?>">
                </label>

                <details name="Alterar Senha">
                    <summary>Alterar Senha</summary>

                    <label>
                        Senha atual (para alterar senha)
                        <input type="password" id="usuario-senha-atual" name="senha_atual"
                            placeholder="••••••••">
                    </label>

                    <label>
                        Nova senha
                        <input type="password" id="usuario-nova-senha" name="nova_senha"
                            placeholder="••••••••">
                    </label>

                    <label>
                        Confirmar nova senha
                        <input type="password" id="usuario-confirmar-senha" name="confirmar_senha"
                            placeholder="••••••••">
                    </label>
                </details>
            </fieldset>

            <fieldset>
                <input type="file" id="usuario-imagem-input" accept="image/*" 
                    style="display: none;">

                <?php if ($user['usuario_imagem_href']): ?>
                    <img id="usuario-imagem-preview" src="<?= $user['usuario_imagem_href'] ?>" alt="Foto de perfil" 
                        style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin: 0 auto;">
                <?php endif ?>

                <button type="button" onclick="document.getElementById('usuario-imagem-input').click()"  style="width: 100%; margin-top: 0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="icon size-6" width="30px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                    </svg>
                    Alterar Foto
                </button>
            </fieldset>

            <footer style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                <button type="button" class="secondary" onclick="this.closest('dialog').close()">
                    Cancelar
                </button>

                <input type="submit" value="Salvar alterações"></input>
            </footer>
        </form>
    </article>
</dialog>