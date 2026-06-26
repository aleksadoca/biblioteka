# Korisnička dokumentacija - Biblioteka

## 1. Pregled aplikacije

Web aplikacija "Biblioteka" je sistem za upravljanje bibliotekom koji omogućava:
- Pregled i pretragu knjiga
- Iznajmljivanje i vraćanje knjiga
- Upravljanje korisnicima
- Praćenje statistika

## 2. Pristupni podaci

### Web sajt
- **URL:** https://zadatak-fakultet.page.gd/
- **Namena domena:** test i prikaz projekta profesoru
- **Hosting:** InfinityFree

### Test nalozi

| Korisničko ime | Lozinka | Uloga | Opis |
|----------------|---------|-------|------|
| admin | password | Zaposleni | Puni pristup svim funkcijama |
| zaposleni1 | password | Zaposleni | Puni pristup svim funkcijama |
| zaposleni2 | password | Zaposleni | Puni pristup svim funkcijama |
| petar | password | Korisnik | Pregled i iznajmljivanje knjiga |
| mika | password | Korisnik | Pregled i iznajmljivanje knjiga |
| zika | password | Korisnik | Pregled i iznajmljivanje knjiga |
| ana | password | Korisnik | Pregled i iznajmljivanje knjiga |
| jelena | password | Korisnik | Pregled i iznajmljivanje knjiga |

### Baza podataka

Podaci za pristup bazi nisu deo korisničke dokumentacije i ne treba ih objavljivati na GitHub-u. Administrator ih unosi u `config/database.php` ili kroz hosting environment vrednosti prema uputstvu iz `README.md`.

## 3. Uputstvo za korišćenje

### 3.1 Prijava u sistem

1. Otvorite `https://zadatak-fakultet.page.gd/`.
2. Kliknite na "Prijava" u navigaciji
3. Unesite korisničko ime i lozinku
4. Kliknite "Prijavi se"

**Nakon prijave:**
- Korisnici vide: Početna, Knjige, Iznajmljivanja, Istorija
- Zaposleni vide dodatno: Korisnici, Statistika

### 3.2 Registracija novog korisnika

1. Kliknite na "Registracija" u navigaciji
2. Popunite sva polja:
   - Puno ime
   - Korisničko ime (min. 3 karaktera)
   - Email adresa
   - Lozinka (min. 6 karaktera)
   - Potvrda lozinke
3. Kliknite "Registruj se"
4. Nakon registracije, prijavite se sa novim podacima

### 3.3 Pregled knjiga

1. Kliknite na "Knjige" u navigaciji
2. Prikazuje se lista svih knjiga sa osnovnim informacijama
3. **Filteri:**
   - Pretraga po naslovu ili autoru
   - Filter po autoru
   - Filter po žanru
   - Filter po godini
4. Kliknite na "Detalji" za više informacija o knjizi

### 3.4 Iznajmljivanje knjige

1. Pronađite željenu knjigu (pregled ili pretraga)
2. Kliknite na "Detalji" pored knjige
3. Kliknite na "Iznajmi knjigu"
4. Izaberite datum vraćanja (podrazumevano 14 dana)
5. Kliknite "Potvrdi iznajmljivanje"

**Pravila:**
- Maksimalno 5 aktivnih iznajmljivanja u isto vreme
- Zakasnina: 100 RSD po danu kašnjenja
- Rok: 14-30 dana

### 3.5 Vraćanje knjige (samo zaposleni)

1. Idite na "Iznajmljivanja"
2. Pronađite aktivno iznajmljivanje
3. Kliknite "Vrati" pored iznajmljivanja
4. Potvrdite vraćanje
5. Ako je zakašnjelo, prikazuje se zakasnina

### 3.6 Pregled istorije

1. Kliknite na "Istorija" u navigaciji
2. Prikazuje se:
   - Statistike (ukupno, aktivno, zakašnjelo, zakasnina)
   - Tabela svih iznajmljivanja sa statusima

### 3.7 Upravljanje knjigama (samo zaposleni)

**Dodavanje nove knjige:**
1. Idite na "Knjige"
2. Kliknite "Dodaj knjigu"
3. Popunite podatke (naslov i autor su obavezni)
4. Kliknite "Sačuvaj knjigu"

**Izmena knjige:**
1. Idite na detalje knjige
2. Kliknite "Izmeni"
3. Promenite željene podatke
4. Kliknite "Sačuvaj izmene"

### 3.8 Upravljanje korisnicima (samo zaposleni)

1. Kliknite na "Korisnici" u navigaciji
2. Prikazuje se lista svih korisnika
3. **Opcije:**
   - Pretraga po imenu, username ili emailu
   - Izmena profila korisnika
   - Promena uloge (user/employee)
   - Pregled istorije korisnika

### 3.9 Statistike (samo zaposleni)

1. Kliknite na "Statistika" u navigaciji
2. Prikazuje se:
   - Osnovne statistike (knjige, korisnici, iznajmljivanja)
   - Top 10 najčitanijih knjiga
   - Top 10 najaktivnijih korisnika
   - Iznajmljivanja po žanrovima

## 4. Česta pitanja

### P: Zaboravio sam lozinku?
O: Trenutno nema opcije za resetovanje lozinke. Kontaktirajte zaposlenog.

### P: Zašto ne mogu da iznajmim knjigu?
O: Mogući razlozi:
- Knjiga nema dostupnih kopija
- Imate 5 aktivnih iznajmljivanja (maksimum)
- Već imate aktivno iznajmljivanje za tu knjigu

### P: Kako se računa zakasnina?
O: 100 RSD po danu kašnjenja. Na primer, ako kasnite 5 dana = 500 RSD.

### P: Da li mogu da produžim rok?
O: Trenutno nema automatskog produženja. Vratite knjigu i ponovo je iznajmite.

## 5. Screenshotovi

Screenshotovi se dodaju nakon postavljanja aplikacije na hosting. Potrebno je sačuvati slike sledećih stranica:
1. Početna stranica
2. Stranica za prijavu
3. Lista knjiga sa filterima
4. Detalji knjige
5. Forma za iznajmljivanje
6. Lista iznajmljivanja
7. Statistike (zaposleni)
8. Lista korisnika (zaposleni)
