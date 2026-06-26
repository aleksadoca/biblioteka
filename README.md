# 📚 Biblioteka - Web Aplikacija za Upravljanje Bibliotekom

PHP web aplikacija za upravljanje bibliotekom, dizajnirana za hosting na InfinityFree platformi.

## 🎯 Opis Projekta

Aplikacija omogućava:

- **Autentifikaciju** - Prijava/odjava korisnika sa dva nivoa pristupa
- **Upravljanje knjigama** - CRUD operacije sa filterima i pretragom
- **Iznajmljivanje** - Izdavanje i vraćanje knjiga sa automatskim računanjem zakasnine
- **Upravljanje korisnicima** - Registracija, profil, istorija
- **Statistike** - Izveštaji o najčitanije knjige i najaktivniji korisnici

## 🏗️ Tehnologije

- **Serverski deo:** PHP 7.4+ (čist PHP, bez framework-a)
- **Baza podataka:** MySQL 5.7+ / MariaDB 10.2+
- **Korisnički interfejs:** HTML5, CSS3, Bootstrap 5, JavaScript
- **Arhitektura:** MVC šablon (modeli, prikazi i kontroleri)

## 📁 Struktura Projekta

```
biblioteka/
├── config/
│   └── database.php          # Konfiguracija baze podataka
├── controllers/
│   ├── BaseController.php    # Zajedničko učitavanje prikaza
│   ├── PageController.php    # Početna, prijava, registracija, odjava
│   ├── BookController.php    # Rute i akcije za knjige
│   ├── RentalController.php  # Rute i akcije za iznajmljivanja
│   ├── UserController.php    # Rute i akcije za korisnike
│   └── StatisticsController.php # Statistika za zaposlene
├── models/
│   ├── User.php             # Model za korisnike
│   ├── Book.php             # Model za knjige
│   └── Rental.php           # Model za iznajmljivanja
├── views/
│   ├── layouts/
│   │   ├── header.php       # Zaglavlje stranice
│   │   └── footer.php       # Podnožje stranice
│   ├── auth/
│   │   ├── login.php        # Stranica za prijavu
│   │   └── register.php     # Stranica za registraciju
│   ├── books/
│   │   ├── index.php        # Lista knjiga sa filterima
│   │   ├── show.php         # Detalji knjige
│   │   ├── create.php       # Dodavanje nove knjige
│   │   └── edit.php         # Izmena knjige
│   ├── rentals/
│   │   ├── index.php        # Lista iznajmljivanja
│   │   ├── create.php       # Novo iznajmljivanje
│   │   └── history.php      # Istorija iznajmljivanja
│   ├── users/
│   │   ├── index.php        # Lista korisnika
│   │   ├── profile.php      # Profil korisnika
│   │   └── edit.php         # Izmena profila
│   └── statistics/
│       └── index.php        # Statistike biblioteke
├── public/
│   ├── index.php            # Glavni ulazni fajl aplikacije
│   ├── .htaccess            # Apache konfiguracija
│   ├── css/
│   │   └── style.css        # Prilagođeni stilovi
│   └── js/
│       └── app.js           # Prilagođeni JavaScript
├── helpers/
│   ├── auth.php             # Pomoćne funkcije za autentifikaciju
│   └── functions.php        # Opšte pomoćne funkcije
├── database/
│   ├── schema.sql           # Struktura baze podataka
│   ├── seed.sql             # Test podaci
│   └── backup.sql           # Kompletni SQL bekap
└── docs/
    ├── er-dijagram.drawio          # ER dijagram (Chen notacija)
    ├── dokumentacija-baze.md       # Dokumentacija baze podataka
    ├── infinityfree-postavka.md    # Uputstvo za InfinityFree hosting
    └── korisnicka-dokumentacija.md # Korisničko uputstvo
```

## 🚀 Instalacija

### Lokalno razvojno okruženje

1. **XAMPP/WAMP/MAMP** - Instalirajte lokalni server
2. **Kopirajte fajlove** u `htdocs` direktorijum
3. **Kreirajte bazu podataka** u phpMyAdmin
4. **Uvezite strukturu** iz `database/schema.sql`
5. **Uvezite test podatke** iz `database/seed.sql`
6. **Pristupite aplikaciji** na `http://localhost/biblioteka/public/`

### InfinityFree hosting

1. **Registrujte nalog** na [InfinityFree](https://infinityfree.net/)
2. **Kreirajte MySQL bazu** u kontrolnom panelu
3. **Ažurirajte** `config/database.php` sa vašim podacima
4. **Upload-ujte fajlove** preko File Manager-a
5. **Pristupite sajtu** na vašem domenu

## ⚙️ Konfiguracija

### Baza podataka

Uredite `config/database.php` na hostingu:

```php
define('DB_HOST', getenv('DB_HOST') ?: 'sqlXXX.infinityfree.com');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'if0_XXXXXXX_biblioteka');
define('DB_USER', getenv('DB_USER') ?: 'if0_XXXXXXX');
define('DB_PASS', getenv('DB_PASS') ?: 'CHANGE_ME');
```

Zamenite prikazane primer vrednosti stvarnim podacima iz InfinityFree kontrolnog panela. Ne objavljujte stvarnu lozinku u repozitorijumu.

## 👥 Korisničke Uloge

### Zaposleni

- Puni pristup svim funkcionalnostima
- Dodavanje, izmena i brisanje knjiga
- Pregled svih korisnika
- Vraćanje knjiga
- Pregled statistika

### Korisnik

- Pregled knjiga
- Iznajmljivanje knjiga
- Pregled sopstvene istorije
- Izmena profila

## 📊 Baza Podataka

### Tabele

- **users** - Korisnici sistema
- **books** - Knjige u biblioteci
- **rentals** - Iznajmljivanja

### Relacije

- `rentals.book_id` → `books.id` (N:1)
- `rentals.user_id` → `users.id` (N:1)

## 🔐 Sigurnost

- **Hashiranje lozinki** - `password_hash()` / `password_verify()`
- **Zaštita od SQL injection napada** - prepared statements za upite ka bazi
- **XSS zaštita** - `htmlspecialchars()` za ispis korisničkih podataka
- **CSRF zaštita** - Token za forme
- **Sesije** - Sigurno upravljanje sesijama

## 📱 Prilagodljiv dizajn

Aplikacija je optimizovana za:

- Desktop računare
- Tablete
- Mobilne telefone

## 🧪 Test Podaci

### Korisnici (lozinka: `password`)

| Korisničko ime | Uloga     | Ime             |
| -------------- | --------- | --------------- |
| admin          | Zaposleni | Marko Marković  |
| zaposleni1     | Zaposleni | Jovan Jovanović |
| petar          | Korisnik  | Petrović Petar  |
| mika           | Korisnik  | Mikić Mika      |

## 📄 Licenca

Ovaj projekat je kreiran u obrazovne svrhe.

## 👨‍ Razvoj

### Pokretanje razvoja

1. Klonirajte repozitorijum
2. Pokrenite lokalni server
3. Kreirajte bazu podataka
4. Pristupite aplikaciji

### Struktura koda

- **MVC šablon** - `index.php` i `public/index.php` rade kao ulazne tačke, kontroleri biraju akcije, modeli rade sa bazom, a prikazi ispisuju HTML.
- **Helper funkcije** - Zajedničke funkcije za sesije, validaciju, prikaz i formatiranje nalaze se u `helpers/`.
- **Ulazna tačka aplikacije** - Zahtevi se rutiraju preko `page` parametra, npr. `index.php?page=books`.

## 📚 Dokumentacija

- [InfinityFree postavka](docs/infinityfree-postavka.md) - Koraci za nalog, bazu, phpMyAdmin, upload fajlova, konfiguraciju i listu potrebnih screenshotova
- [ER Dijagram](docs/er-dijagram.drawio) - Chen notacija u draw.io formatu
- [Dokumentacija baze](docs/dokumentacija-baze.md) - Model, relacije, normalizacija 3NF
- [Korisničko uputstvo](docs/korisnicka-dokumentacija.md) - Uputstvo za korišćenje

## 🔄 GitHub predaja

Demo domen za test i prikaz: `https://zadatak-fakultet.page.gd/`.

U predaji profesoru priložiti stvarni GitHub link repozitorijuma.

Preporučena struktura commit-ova za GitHub repozitorijum:

1. **Inicijalni commit** - Osnovna struktura projekta
   - Kreiranje folder strukture
   - Konfiguracija baze podataka
   - Helper funkcije

2. **Modeli** - Implementacija modela
   - User model
   - Book model
   - Rental model

3. **Autentifikacija** - Sistem za prijavu/odjavu
   - Login stranica
   - Registracija stranica
   - Sesije i autorizacija

4. **Books modul** - CRUD operacije za knjige
   - Lista knjiga sa filterima
   - Dodavanje/izmena knjiga
   - Detalji knjige

5. **Rentals modul** - Sistem iznajmljivanja
   - Iznajmljivanje knjiga
   - Vraćanje knjiga
   - Zakasnina

6. **Users modul** - Upravljanje korisnicima
   - Lista korisnika
   - Profil korisnika
   - Izmena profila

7. **Statistika** - Izveštaji
   - Statistike po žanru
   - Najaktivniji korisnici
   - Najčitanije knjige

8. **Korisnički interfejs** - CSS i JavaScript
   - Bootstrap raspored
   - Prilagođeni stilovi
   - Interaktivnost

9. **Dokumentacija** - Završna dokumentacija
   - ER dijagram
   - Dokumentacija baze
   - Korisničko uputstvo
   - SQL bekap

---

**Napomena:** Projekat je namenjen za školski zadatak. Pre javne produkcione upotrebe potrebno je dodatno proveriti hosting podešavanja, backup politiku i zaštitu administratorskih naloga.
