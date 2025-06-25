# MovieReviewHub

**MovieReviewHub** to aplikacja Symfony 6 umożliwiająca:

- Rejestrację i logowanie użytkowników
- Dodawanie filmów po tytule (dane pobierane z OMDb API)
- Przeglądanie listy i szczegółów filmów
- Dodawanie, edycję i usuwanie własnych recenzji
- Zarządzanie profilem (username, email, lista recenzji)

---

## Wymagania

- Docker & Docker Compose
- PHP 8.2+
- Klucz OMDb w `.env`:
  ```dotenv
  APP_SECRET=<losowy_ciag_znakow>
  OMDB_API_KEY=<twoj_klucz>
  DATABASE_URL="postgresql://moviehub:test@database:5432/movie_review_hub"

---

# Szybki start

1.**Klonuj repozytorium**
```bash
git clone https://github.com/eugene817/MovieReviewHub.git && cd MovieReviewHub
cp .env.example .env
# uzupełnij APP_SECRET i OMDB_API_KEY
```

2. **Uruchom kontenery**
```bash
docker-compose up --build -d
```

3. **Przygotuj bazę**
```bash
docker-compose exec php php bin/console doctrine:database:create --if-not-exists
docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction
docker-compose exec php php bin/console doctrine:fixtures:load --no-interaction --append
```

4. **Dostęp**
Aplikacja: http://localhost:8000
