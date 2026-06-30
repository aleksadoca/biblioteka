// Biblioteka - dodatni JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Automatsko zatvaranje poruka nakon 5 sekundi
    document.querySelectorAll('.alert').forEach(function(alert) {
        setTimeout(function() {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 5000);
    });

    // Potvrda pre brisanja
    document.querySelectorAll('[data-confirm]').forEach(function(dugme) {
        dugme.addEventListener('click', function(e) {
            var poruka = this.getAttribute('data-confirm') || 'Da li ste sigurni?';
            if (!confirm(poruka)) {
                e.preventDefault();
            }
        });
    });

    // Izbor knjige na stranici za iznajmljivanje
    var selectKnjiga = document.getElementById('book_id');
    if (selectKnjiga) {
        selectKnjiga.addEventListener('change', function() {
            if (this.value) {
                window.location.href = '/index.php?page=rental_create&book_id=' + this.value;
            }
        });
    }

    // Provera lozinki na registraciji
    var formaRegistracija = document.querySelector('form[action*="register"]');
    if (formaRegistracija) {
        formaRegistracija.addEventListener('submit', function(e) {
            var lozinka = document.getElementById('password');
            var potvrda = document.getElementById('password_confirm');
            if (lozinka && potvrda && lozinka.value !== potvrda.value) {
                e.preventDefault();
                alert('Lozinke se ne podudaraju!');
                potvrda.focus();
            }
        });
    }

    // Provera lozinki na izmeni profila
    var formaProfil = document.querySelector('form[action*="user_edit"]');
    if (formaProfil) {
        formaProfil.addEventListener('submit', function(e) {
            var lozinka = document.getElementById('password');
            var potvrda = document.getElementById('password_confirm');
            if (lozinka && potvrda && lozinka.value && lozinka.value !== potvrda.value) {
                e.preventDefault();
                alert('Lozinke se ne podudaraju!');
                potvrda.focus();
            }
        });
    }

    // Pretraga knjiga - slanje forme posle kratke pauze
    var poljePretrage = document.getElementById('search');
    if (poljePretrage) {
        var tajmer;
        poljePretrage.addEventListener('input', function() {
            clearTimeout(tajmer);
            tajmer = setTimeout(function() {
                var forma = poljePretrage.closest('form');
                if (forma) {
                    forma.submit();
                }
            }, 500);
        });
    }
});
