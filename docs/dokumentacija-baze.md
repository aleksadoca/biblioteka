# Dokumentacija baze podataka - Biblioteka

## 1. ER Dijagram

ER dijagram je kreiran u Chen notaciji pomoću draw.io alata. Fajl se nalazi u: `docs/er-dijagram.drawio`

### Entiteti i atributi

#### USERS (Korisnici)
| Atribut | Tip | Opis |
|---------|-----|------|
| id (PK) | INT AUTO_INCREMENT | Jedinstveni identifikator |
| username | VARCHAR(50) UNIQUE | Korisničko ime |
| password | VARCHAR(255) | Hash-irana lozinka |
| email | VARCHAR(100) UNIQUE | Email adresa |
| full_name | VARCHAR(100) | Puno ime korisnika |
| role | ENUM('user','employee') | Uloga u sistemu |
| created_at | TIMESTAMP | Datum registracije |

#### BOOKS (Knjige)
| Atribut | Tip | Opis |
|---------|-----|------|
| id (PK) | INT AUTO_INCREMENT | Jedinstveni identifikator |
| title | VARCHAR(200) | Naslov knjige |
| author | VARCHAR(100) | Autor knjige |
| isbn | VARCHAR(20) UNIQUE | ISBN broj |
| genre | VARCHAR(50) | Žanr knjige |
| year | INT | Godina izdanja |
| available_copies | INT | Broj dostupnih kopija |
| total_copies | INT | Ukupan broj kopija |
| description | TEXT | Opis knjige |
| cover_image | VARCHAR(255) | URL slike korice |

#### RENTALS (Iznajmljivanja)
| Atribut | Tip | Opis |
|---------|-----|------|
| id (PK) | INT AUTO_INCREMENT | Jedinstveni identifikator |
| book_id (FK) | INT UNSIGNED | Strani ključ ka BOOKS |
| user_id (FK) | INT UNSIGNED | Strani ključ ka USERS |
| rental_date | DATE | Datum iznajmljivanja |
| due_date | DATE | Rok za vraćanje |
| return_date | DATE | Datum vraćanja (NULL ako nije vraćeno) |
| status | ENUM('active','returned','late') | Status iznajmljivanja |
| late_fee | DECIMAL(10,2) | Zakasnina u RSD |

## 2. Relacije

### USERS → RENTALS (1:N)
- Jedan korisnik može imati više iznajmljivanja
- Kardinalnost: 1:N
- Strani ključ: `rentals.user_id` → `users.id`

### BOOKS → RENTALS (1:N)
- Jedna knjiga može biti iznajmljena više puta
- Kardinalnost: 1:N
- Strani ključ: `rentals.book_id` → `books.id`

### Asocijativna entiteta
- RENTALS je asocijativna entiteta koja povezuje USERS i BOOKS
- Predstavlja relaciju "iznajmljivanje" između korisnika i knjiga

## 3. Objašnjenje modela

### Zašto su određene tabele kreirane

**USERS tabela:**
- Centralna tabela za autentifikaciju i autorizaciju
- Čuva podatke o korisnicima sistema
- Razdvajanje uloga (user/employee) omogućava kontrolu pristupa

**BOOKS tabela:**
- Centralna tabela za upravljanje inventarom
- Prati dostupnost kopija u realnom vremenu
- Omogućava filtriranje i pretragu po različitim kriterijumima

**RENTALS tabela:**
- Povezuje korisnike sa knjigama kroz transakcije
- Prati status iznajmljivanja (aktivno, vraćeno, zakasnelo)
- Čuva iznos zakasnine koji aplikacija računa prilikom vraćanja knjige

## 4. Normalizacija (do 3NF)

### Prvi normalni oblik (1NF)
✅ **Svi atributi su atomarni** - svaka ćelija sadrži samo jednu vrednost
- `full_name` je jedna tekstualna vrednost za puno ime korisnika
- `role` je enum sa fiksnim vrednostima
- Nema ponavljajućih grupa

### Drugi normalni oblik (2NF)
✅ **Svi ne-ključni atributi su potpuno funkcionalni zavisni od primarnog ključa**
- U tabeli USERS: svi atributi zavise samo od `id`
- U tabeli BOOKS: svi atributi zavise samo od `id`
- U tabeli RENTALS: svi atributi zavise samo od `id` (ne od kombinacije book_id + user_id)

### Treći normalni oblik (3NF)
✅ **Nema tranzitivnih zavisnosti**
- U tabeli USERS: `role` ne zavisi od drugih ne-ključnih atributa
- U tabeli BOOKS: `genre` ne zavisi od `author` ili `year`
- U tabeli RENTALS: `late_fee` se računa na osnovu `due_date` i `return_date`, ne zavisi od drugih atributa

### Zašto nema posebnih tabela za žanrove i autore?
- **Žanrovi** su u ovom zadatku atribut knjige, jer je cilj jednostavan model sa obaveznim tabelama.
- **Autori** su takođe atribut knjige; u većem sistemu mogli bi biti posebna tabela.
- Za potrebe ovog zadatka, ovo je optimalno rešenje

## 5. Indeksi

### Primarni ključevi
- `users.id` - AUTO_INCREMENT
- `books.id` - AUTO_INCREMENT
- `rentals.id` - AUTO_INCREMENT

### Jedinstveni indeksi
- `users.username` - sprečava duplikate korisničkih imena
- `users.email` - sprečava duplikate email adresa
- `books.isbn` - sprečava duplikate ISBN brojeva

### Strani ključevi
- `rentals.book_id` → `books.id` (CASCADE na UPDATE, RESTRICT na DELETE)
- `rentals.user_id` → `users.id` (CASCADE na UPDATE, RESTRICT na DELETE)

### Pretraga indeksi
- `idx_author` - za pretragu po autoru
- `idx_genre` - za filtriranje po žanru
- `idx_year` - za filtriranje po godini
- `idx_status` - za filtriranje po statusu iznajmljivanja
- `idx_rental_date` - za sortiranje po datumu iznajmljivanja
- `idx_due_date` - za pronalaženje zakasnelih iznajmljivanja

## 6. SQL skripta

Struktura baze podataka se nalazi u `database/schema.sql`
Test podaci se nalaze u `database/seed.sql`

### Kreiranje baze
```sql
-- Pokrenuti u phpMyAdmin ili MySQL klijentu
SOURCE database/schema.sql;
SOURCE database/seed.sql;
```

## 7. Prikazi (Views)

### v_active_rentals
Prikazuje aktivna iznajmljivanja sa izračunatom zakasninom:
- Automatski računa zakasninu na osnovu trenutnog datuma
- Prikazuje broj dana kašnjenja

### v_rental_stats
Statistike iznajmljivanja po žanrovima:
- Ukupan broj iznajmljivanja po žanru
- Broj vraćenih i zakasnelih
- Ukupna zakasnina po žanru
