# budget-management
1. Wstęp

Jest to aplikacja Symfony do zarządzania budżetem, która umożliwia użytkownikom dodawanie i usuwanie wydatków. Wydatki mogą mieć id, kategorię oraz wartość. Użytkownik na stronie głównej widzi zestawienie wszystkich wydatków oraz sumę wydatków.

2. Wymagania

Docker
Docker Compose

3. Instalacja

Sklonuj repozytorium:

```bash
git clone https://github.com/your-repo/budget-management.git
cd budget-management
```

4. Uruchomienie środowiska

Uruchom Docker:

```bash
docker-compose up --build -d

```
Wejdź do kontenera PHP:
```bash
docker-compose exec app bash

```
Zainstaluj zależności PHP:
```bash
composer install
```

6. Załadowanie danych testowych (fixtur)

Załaduj dane testowe:

```bash
php bin/console doctrine:fixtures:load
```
7. Obsługa aplikacji

Strona główna (Lista wydatków): GET /expense
Dodawanie nowego wydatku: GET /expense/new, POST /expense/new
Usuwanie wydatku: POST /expense/{id}/delete

8. Własna komenda

Uruchomienie własnej komendy do dodawania wydatku:

```bash
php bin/console app:add-expense
```

9. Proste zwrotki API

Pobierz wszystkie wydatki: GET /api/expenses
Dodaj nowy wydatek: POST /api/expenses
Przykładowe dane dla POST /api/expenses:

```json
{
    "category": "Food",
    "amount": 100.50,
    "date": "2024-07-01T00:00:00Z"
}
```