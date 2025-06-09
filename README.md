# JobNet - Guida all’Installazione

JobNet è un'applicazione Laravel containerizzata con Docker, pensata per facilitare lo sviluppo e la gestione di una piattaforma di recruiting.

## Requisiti

- Docker
- Docker Compose

## Installazione

1. **Clona il repository:**
   ```bash
   git clone https://github.com/Lionjj/Jobnet.git
   cd Jobnet
   ```

2. **Avvia i container:**
   ```bash
   docker-compose up --build -d
   ```

3. **Installa le dipendenze e avvia Vite:**
   Questo avviene automaticamente all'interno del container `vite`.

4. **Migrazioni e seeding del database:**
   Entra nel container `laravel_app` ed esegui:
   ```bash
   docker exec -it laravel_app bash
   php artisan migrate --seed
   ```

## Servizi disponibili

| Servizio       | Descrizione                             | Porta locale |
|----------------|-----------------------------------------|--------------|
| App Laravel    | Applicazione principale                 | 8080         |
| MySQL          | Database MySQL                          | 3306         |
| phpMyAdmin     | Interfaccia DB                          | 8081         |
| Redis          | Cache e Queue                           | 6379         |
| Mailhog        | Visualizzazione email test              | 8025         |
| Vite           | Dev server frontend                     | 5173         |

## Volumi e Reti

- Volume persistente per MySQL: `dbdata`
- Rete Docker: `laravel`

## Note aggiuntive

- Se si verificano problemi con Vite, assicurarsi che le porte non siano occupate.
- La configurazione NGINX è in `./docker/nginx/default.conf`.

---

© 2025 JobNet - Lionjj
