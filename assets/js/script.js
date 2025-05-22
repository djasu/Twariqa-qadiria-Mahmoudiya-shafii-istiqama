// Activation des tooltips Bootstrap
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    
    // Confirmation avant suppression
    $('.delete-btn').on('click', function() {
        return confirm('Êtes-vous sûr de vouloir supprimer cet élément?');
    });
});

// Afficher/masquer le mot de passe
$('.toggle-password').on('click', function() {
    const input = $(this).siblings('input');
    const icon = $(this).find('i');
    
    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('bi-eye').addClass('bi-eye-slash');
    } else {
        input.attr('type', 'password');
        icon.removeClass('bi-eye-slash').addClass('bi-eye');
    }
});