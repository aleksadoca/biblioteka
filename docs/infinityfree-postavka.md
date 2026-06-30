# Uputstvo za postavku na InfinityFree

Ovaj dokument opisuje kako se aplikacija Biblioteka postavlja na InfinityFree hosting.

Demo domen za test i prikaz projekta je:

`https://zadatak-fakultet.page.gd/`

Domen služi za proveru školskog projekta. Privatni hosting, FTP i phpMyAdmin podaci ne objavljuju se u repozitorijumu.

## 1. Kreiranje InfinityFree naloga

1. Otvoriti <https://infinityfree.net/>.
2. Kliknuti na **Sign Up**.
3. Uneti email adresu i lozinku za nalog.
4. Potvrditi email adresu.
5. Otvoriti InfinityFree klijentski panel.

## 2. Kreiranje hosting naloga

1. U klijentskom panelu kliknuti na **Create Account**.
2. Izabrati besplatan subdomen ili povezati sopstveni domen.
3. Sačekati da status hosting naloga bude aktivan.
4. Otvoriti kontrolni panel za taj hosting nalog.

## 3. Postavka MySQL baze

1. U kontrolnom panelu otvoriti **MySQL Databases**.
2. Kreirati novu bazu, na primer `biblioteka`.
3. Sačuvati generisane podatke:
   - MySQL host
   - ime baze
   - korisničko ime baze
   - lozinku baze
4. Ovi podaci su privatni i ne postavljaju se na GitHub.

## 4. Import baze kroz phpMyAdmin

1. Iz InfinityFree kontrolnog panela otvoriti **phpMyAdmin**.
2. Izabrati kreiranu bazu.
3. Otvoriti karticu **Import**.
4. Uvesti fajl `database/backup.sql`, jer sadrži strukturu i početne podatke.
5. Kliknuti na **Go** i proveriti da postoje tabele `users`, `books` i `rentals`.

Druga mogućnost je da se prvo uveze `database/schema.sql`, pa zatim `database/seed.sql`.

## 5. Konfiguracija konekcije

Pre uploada otvoriti `config/database.php` i zameniti primer vrednosti podacima iz InfinityFree panela.

```php
define('DB_HOST', getenv('DB_HOST') ?: 'sqlXXX.infinityfree.com');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'if0_XXXXXXX_biblioteka');
define('DB_USER', getenv('DB_USER') ?: 'if0_XXXXXXX');
define('DB_PASS', getenv('DB_PASS') ?: 'CHANGE_ME');
```

Stvarna lozinka se unosi samo na hostingu i ne objavljuje se u repozitorijumu.

## 6. Upload fajlova

1. Otvoriti **File Manager** ili se povezati preko FTP-a.
2. Ući u `htdocs/`.
3. Uploadovati sadržaj foldera `biblioteka/` direktno u `htdocs/`.
4. Posle uploada u `htdocs/` treba da se vide `index.php`, `.htaccess`, `config/`, `controllers/`, `models/`, `views/`, `helpers/`, `database/`, `css/`, `public/` i `docs/`.
5. Ne uploadovati spoljašnji folder tako da putanja bude `htdocs/biblioteka/index.php`, jer tada domen ne bi radio direktno.
6. Demo domen mora da otvara aplikaciju direktno na `https://zadatak-fakultet.page.gd/`, bez `/public` u URL-u.
7. Baza se postavlja kroz phpMyAdmin import fajla `database/backup.sql`; posebne instalacione PHP skripte nisu deo predajne verzije.

## 7. Provera rada aplikacije

1. Otvoriti demo domen: `https://zadatak-fakultet.page.gd/`.
2. Proveriti da naslov početne stranice prikazuje "Biblioteka" i da se vidi lista najnovijih knjiga.
3. Prijaviti se kao zaposleni:
   - korisničko ime: `admin`
   - lozinka: `password`
4. Proveriti listu knjiga, filtere, dodavanje/izmenu knjige, vraćanje knjige, korisnike i statistiku.
5. Prijaviti se kao običan korisnik:
   - korisničko ime: `petar`
   - lozinka: `password`
6. Proveriti da običan korisnik ne može da pristupi stranicama za zaposlene.
7. Proveriti da ne postoje javno dostupne pomoćne skripte kao `setup.php`, `import_db.php`, `fix_db.php` ili `test.php`.

Ekrani same aplikacije nalaze se u `docs/screenshots/` i prikazani su u `README.md` i Word dokumentaciji.

## 8. Checklist pre predaje projekta

- Demo domen otvara aplikaciju direktno: `https://zadatak-fakultet.page.gd/`, bez `/public` u URL-u.
- `config/database.php` na hostingu ima stvarne podatke baze, ali repozitorijum ima samo primer vrednosti.
- Baza je importovana iz `database/backup.sql`.
- Test nalog `admin` / `password` radi.
- Test nalog `petar` / `password` radi.
- CRUD za knjige radi za zaposlenog.
- Običan korisnik ne može da pristupi korisnicima/statistici.
- `setup.php`, `import_db.php`, `fix_db.php` i `test.php` nisu javno dostupni na hostingu.
- Ako je neka instalaciona skripta ranije javno prikazala MySQL lozinku, lozinka baze je promenjena u InfinityFree panelu i zatim ažurirana u `config/database.php` na hostingu.
- Dokumentacija, ER dijagram i SQL fajlovi su priloženi.

## 9. Pokretanje na drugom hostingu

1. Na novom hostingu kreirati MySQL bazu.
2. Uvesti `database/backup.sql` kroz phpMyAdmin ili MySQL klijent.
3. U `config/database.php` upisati host, ime baze, korisnika i lozinku nove baze.
4. Uploadovati fajlove aplikacije.
5. Proveriti prijavu, CRUD operacije nad knjigama, iznajmljivanja i statistiku.
