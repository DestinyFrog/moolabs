
function toggle_modal_usuario() {
    const dialog = document.getElementById("modal-editar-usuario")
    if (dialog.open)
        dialog.close()
    else
        dialog.showModal()
}