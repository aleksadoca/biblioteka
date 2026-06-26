/**
 * Biblioteka - Prilagođeni JavaScript
 * Dodatna interaktivnost za aplikacija
 */

// Čekanje da se DOM u potpunosti učita
document.addEventListener('DOMContentLoaded', function() {
    
    // =============================================
    // Automatsko zatvaranje alert poruka
    // =============================================
    
    // Selektovanje svih alert poruka
    const alertPoruke = document.querySelectorAll('.alert');
    
    // Postavljanje tajmera za automatsko zatvaranje
    alertPoruke.forEach(function(alert) {
        // Zatvaranje nakon 5 sekundi
        setTimeout(function() {
            // Kreiranje Bootstrap alert objekta
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // =============================================
    // Potvrda brisanja
    // =============================================
    
    // Dodavanje potvrde za sve dugmad za brisanje
    const dugmadBrisanje = document.querySelectorAll('[data-confirm]');
    
    dugmadBrisanje.forEach(function(dugme) {
        dugme.addEventListener('click', function(e) {
            // Dohvatanje poruke za potvrdu
            const poruka = this.getAttribute('data-confirm') || 'Da li ste sigurni?';
            
            // Prikaz dijaloga za potvrdu
            if (!confirm(poruka)) {
                e.preventDefault();
            }
        });
    });
    
    // =============================================
    // Dinamičko učitavanje detalja knjige
    // =============================================
    
    // Selektovanje select elementa za knjige
    const selectKnjiga = document.getElementById('book_id');
    
    if (selectKnjiga) {
        selectKnjiga.addEventListener('change', function() {
            // Dohvatanje ID izabrane knjige
            const bookId = this.value;
            
            if (bookId) {
                // Preusmeravanje na stranicu za iznajmljivanje sa izabranom knjigom
                window.location.href = '/index.php?page=rental_create&book_id=' + bookId;
            }
        });
    }
    
    // =============================================
    // Validacija forme za registraciju
    // =============================================
    
    // Selektovanje forme za registraciju
    const formaRegistracija = document.querySelector('form[action*="register"]');
    
    if (formaRegistracija) {
        formaRegistracija.addEventListener('submit', function(e) {
            // Dohvatanje polja
            const lozinka = document.getElementById('password');
            const potvrda = document.getElementById('password_confirm');
            
            // Provera podudaranja lozinki
            if (lozinka && potvrda && lozinka.value !== potvrda.value) {
                e.preventDefault();
                alert('Lozinke se ne podudaraju!');
                potvrda.focus();
            }
        });
    }
    
    // =============================================
    // Validacija forme za izmenu profila
    // =============================================
    
    // Selektovanje forme za izmenu profila
    const formaProfil = document.querySelector('form[action*="user_edit"]');
    
    if (formaProfil) {
        formaProfil.addEventListener('submit', function(e) {
            // Dohvatanje polja
            const lozinka = document.getElementById('password');
            const potvrda = document.getElementById('password_confirm');
            
            // Provera podudaranja lozinki (samo ako su unete)
            if (lozinka && potvrda && lozinka.value && lozinka.value !== potvrda.value) {
                e.preventDefault();
                alert('Lozinke se ne podudaraju!');
                potvrda.focus();
            }
        });
    }
    
    // =============================================
    // Prikaz/skrivanje lozinke
    // =============================================
    
    // Kreiranje dugmadi za prikaz lozinke
    const poljaLozinke = document.querySelectorAll('input[type="password"]');
    
    poljaLozinke.forEach(function(polje) {
        // Kreiranje kontejnera
        const kontejner = document.createElement('div');
        kontejner.className = 'input-group';
        
        // Premještanje polja u kontejner
        polje.parentNode.insertBefore(kontejner, polje);
        kontejner.appendChild(polje);
        
        // Kreiranje dugmeta
        const dugme = document.createElement('button');
        dugme.type = 'button';
        dugme.className = 'btn btn-outline-secondary';
        dugme.innerHTML = '<i class="bi bi-eye"></i>';
        dugme.title = 'Prikaži lozinku';
        
        // Dodavanje dugmeta u kontejner
        kontejner.appendChild(dugme);
        
        // Obrada klika na dugme
        dugme.addEventListener('click', function() {
            // Promena tipa polja
            if (polje.type === 'password') {
                polje.type = 'text';
                this.innerHTML = '<i class="bi bi-eye-slash"></i>';
                this.title = 'Sakrij lozinku';
            } else {
                polje.type = 'password';
                this.innerHTML = '<i class="bi bi-eye"></i>';
                this.title = 'Prikaži lozinku';
            }
        });
    });
    
    // =============================================
    // Animacija za kartice
    // =============================================
    
    // Selektovanje svih kartica
    const kartice = document.querySelectorAll('.card');
    
    // Kreiranje Intersection Observer za animaciju
    const observer = new IntersectionObserver(function(unosi) {
        unosi.forEach(function(unos) {
            if (unos.isIntersecting) {
                unos.target.style.opacity = '1';
                unos.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });
    
    // Postavljanje početnog stanja i pokretanje posmatranja
    kartice.forEach(function(kartica) {
        kartica.style.opacity = '0';
        kartica.style.transform = 'translateY(20px)';
        kartica.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(kartica);
    });
    
    // =============================================
    // Tooltip inicijalizacija
    // =============================================
    
    // Inicijalizacija Bootstrap tooltip-ova
    const tooltipTrigeri = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTrigeri.forEach(function(triger) {
        new bootstrap.Tooltip(triger);
    });
    
    // =============================================
    // Pretraga u realnom vremenu
    // =============================================
    
    // Selektovanje polja za pretragu
    const poljePretrage = document.getElementById('search');
    
    if (poljePretrage) {
        let tajmer;
        
        poljePretrage.addEventListener('input', function() {
            // Debounce funkcija - čeka 500ms nakon prestanka kucanja
            clearTimeout(tajmer);
            
            tajmer = setTimeout(function() {
                // Automatsko slanje forme nakon 500ms
                const forma = poljePretrage.closest('form');
                if (forma) {
                    forma.submit();
                }
            }, 500);
        });
    }
    
    // =============================================
    // Print funkcionalnost
    // =============================================
    
    // Dugme za štampanje
    const dugmeStampaj = document.getElementById('btn-stampaj');
    
    if (dugmeStampaj) {
        dugmeStampaj.addEventListener('click', function() {
            window.print();
        });
    }
    
    // =============================================
    // Datumski pikker
    // =============================================
    
    // Inicijalizacija datumpskih pikera
    const datumskiPikeri = document.querySelectorAll('input[type="date"]');
    
    datumskiPikeri.forEach(function(piker) {
        // Postavljanje minimalnog datuma na danas
        if (!piker.min) {
            const danas = new Date().toISOString().split('T')[0];
            piker.min = danas;
        }
    });
    
    // =============================================
    // Animacija brojeva
    // =============================================
    
    // Funkcija za animaciju brojeva
    function animirajBroj(element, ciljaniBroj) {
        let trenutniBroj = 0;
        const korak = Math.ceil(ciljaniBroj / 50);
        
        const interval = setInterval(function() {
            trenutniBroj += korak;
            
            if (trenutniBroj >= ciljaniBroj) {
                trenutniBroj = ciljaniBroj;
                clearInterval(interval);
            }
            
            element.textContent = trenutniBroj;
        }, 20);
    }
    
    // Primena animacije na statističke kartice
    const statistickiElementi = document.querySelectorAll('.card h2');
    
    statistickiElementi.forEach(function(element) {
        const broj = parseInt(element.textContent);
        
        if (!isNaN(broj) && broj > 0) {
            element.textContent = '0';
            animirajBroj(element, broj);
        }
    });
    
});

// =============================================
// Pomoćne funkcije
// =============================================

/**
 * Formatiranje datuma u srpski format
 * @param {string} datum - Datum u ISO formatu
 * @returns {string} Formatirani datum
 */
function formatirajDatum(datum) {
    if (!datum) return '-';
    
    const d = new Date(datum);
    const dan = d.getDate().toString().padStart(2, '0');
    const mesec = (d.getMonth() + 1).toString().padStart(2, '0');
    const godina = d.getFullYear();
    
    return `${dan}.${mesec}.${godina}`;
}

/**
 * Formatiranje valute
 * @param {number} iznos - Iznos za formatiranje
 * @returns {string} Formatirani iznos
 */
function formatirajValutu(iznos) {
    return new Intl.NumberFormat('sr-RS', {
        style: 'currency',
        currency: 'RSD'
    }).format(iznos);
}

/**
 * Prikaz notifikacije
 * @param {string} poruka - Tekst notifikacije
 * @param {string} tip - Tip notifikacije (success, error, warning, info)
 */
function prikaziNotifikaciju(poruka, tip) {
    // Kreiranje elementa za notifikaciju
    const notifikacija = document.createElement('div');
    notifikacija.className = `alert alert-${tip} alert-dismissible fade show position-fixed`;
    notifikacija.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notifikacija.innerHTML = `
        ${poruka}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Dodavanje na stranicu
    document.body.appendChild(notifikacija);
    
    // Automatsko uklanjanje nakon 5 sekundi
    setTimeout(function() {
        const bsAlert = new bootstrap.Alert(notifikacija);
        bsAlert.close();
    }, 5000);
}
