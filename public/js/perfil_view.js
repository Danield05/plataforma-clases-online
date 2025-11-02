// Manejar subida de foto de perfil
document.addEventListener('DOMContentLoaded', function() {
    const photoContainer = document.querySelector('.profile-avatar-container');
    const photoInput = document.getElementById('profilePhotoInput');
    const photoDisplay = document.getElementById('profilePhotoDisplay');
    const avatarDisplay = document.getElementById('profileAvatarDisplay');

    // Clic en el contenedor abre el selector de archivos
    photoContainer.addEventListener('click', function() {
        photoInput.click();
    });

    // Cuando se selecciona un archivo
    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        // Validar tipo de archivo
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Por favor selecciona una imagen válida (JPG, PNG, GIF o WebP)');
            return;
        }

        // Validar tamaño (5MB máximo)
        if (file.size > 5 * 1024 * 1024) {
            alert('La imagen es demasiado grande. Máximo 5MB');
            return;
        }

        // Mostrar preview
        const reader = new FileReader();
        reader.onload = function(e) {
            // Si existe foto, actualizar src; si no, crear elemento img
            if (photoDisplay) {
                photoDisplay.src = e.target.result;
            } else if (avatarDisplay) {
                // Reemplazar avatar con imagen
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Foto de perfil';
                img.className = 'profile-photo';
                img.id = 'profilePhotoDisplay';
                avatarDisplay.parentNode.replaceChild(img, avatarDisplay);
            }
        };
        reader.readAsDataURL(file);

        // Subir archivo
        uploadProfilePhoto(file);
    });

    function uploadProfilePhoto(file) {
        const formData = new FormData();
        formData.append('profile_photo', file);

        // Mostrar loading
        const overlay = document.querySelector('.profile-photo-overlay');
        if (overlay) {
            overlay.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Subiendo...</span>';
            overlay.style.opacity = '1';
        }

        fetch('/plataforma-clases-online/home/upload_profile_photo', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Verificar si la respuesta es válida
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // Verificar el tipo de contenido
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                // Si no es JSON, probablemente es un error HTML
                return response.text().then(text => {
                    console.error('Respuesta no JSON:', text);
                    throw new Error('El servidor devolvió una respuesta no válida');
                });
            }

            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Actualizar imagen con la URL del servidor
                const img = document.getElementById('profilePhotoDisplay');
                if (img) {
                    img.src = data.photo_url;
                }

                // Actualizar también la foto en la navegación
                updateNavProfilePhoto(data.photo_url);

                // Mostrar mensaje de éxito
                showMessage('Foto de perfil actualizada correctamente', 'success');
            } else {
                showMessage(data.message || 'Error al subir la imagen', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al subir la imagen', 'error');
        })
        .finally(() => {
            // Restaurar overlay
            if (overlay) {
                overlay.innerHTML = '<i class="fas fa-camera"></i><span>Cambiar foto</span>';
                overlay.style.opacity = '0';
            }
        });
    }

    function showMessage(message, type) {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        // Auto-remover después de 3 segundos
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }

    function updateNavProfilePhoto(photoUrl) {
        // Buscar el avatar en la navegación
        const navAvatar = document.querySelector('.user-avatar-small');
        if (navAvatar) {
            // Verificar si ya hay una imagen
            const existingImg = navAvatar.querySelector('.nav-profile-photo');
            if (existingImg) {
                // Actualizar la imagen existente
                existingImg.src = photoUrl;
            } else {
                // Reemplazar el contenido del avatar con una imagen
                navAvatar.innerHTML = `<img src="${photoUrl}" alt="Foto de perfil" class="nav-profile-photo">`;
                // Remover clases de color de avatar
                navAvatar.className = navAvatar.className.replace(/nav-avatar-\d+/g, '');
            }
        }
    }
});