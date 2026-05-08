# Deploy cPanel - kontabilitaonline.it

Questa procedura è pensata per cPanel senza terminale, con document root bloccata su `/public_html`.

## 1. Clona il repository fuori da public_html

In cPanel apri **Git Version Control** e crea un clone:

- Clone URL: `https://github.com/lauruccia/kontabilit-.git`
- Repository Path: `kontabilit`

Il risultato deve essere:

```text
/home/CPANEL_USER/kontabilit
```

Non clonare il repository dentro `/public_html`.

## 2. Configura public_html

Da File Manager:

1. Apri `/home/CPANEL_USER/kontabilit/public`
2. Copia tutto il contenuto dentro `/home/CPANEL_USER/public_html`
3. Sostituisci `/home/CPANEL_USER/public_html/index.php` con il contenuto di:

```text
/home/CPANEL_USER/kontabilit/deployment/cpanel-public-html-index.php
```

Dentro `/public_html` devono esserci solo file pubblici come:

```text
index.php
.htaccess
build/
images/
favicon.ico
```

## 3. Crea database MySQL

In cPanel apri **MySQL Databases**:

1. Crea un database, ad esempio `CPANEL_USER_kosmos`
2. Crea un utente database, ad esempio `CPANEL_USER_kosmosuser`
3. Assegna l'utente al database con **All Privileges**

## 4. Crea il file .env

In File Manager copia:

```text
/home/CPANEL_USER/kontabilit/.env.cpanel.example
```

e rinominalo in:

```text
/home/CPANEL_USER/kontabilit/.env
```

Poi modifica questi valori:

```env
APP_URL=https://kontabilitaonline.it
DB_DATABASE=CPANEL_USER_kosmos
DB_USERNAME=CPANEL_USER_kosmosuser
DB_PASSWORD=password_database
MAIL_PASSWORD=password_email
DEPLOY_TOKEN=una-stringa-lunga-casuale
```

Lascia:

```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=localhost
```

## 5. Carica vendor se Composer non è disponibile

Se cPanel non ha Composer, carica la cartella locale `vendor/` dentro:

```text
/home/CPANEL_USER/kontabilit/vendor
```

## 6. Avvia database e cache dal browser

Verifica che `deploy-once.php` sia presente in:

```text
/home/CPANEL_USER/public_html/deploy-once.php
```

Poi apri:

```text
https://kontabilitaonline.it/deploy-once.php?token=VALORE_DEPLOY_TOKEN
```

Quando termina, cancella subito:

```text
/home/CPANEL_USER/public_html/deploy-once.php
```

e rimuovi `DEPLOY_TOKEN` dal file `.env`.

## 7. Test

Apri:

```text
https://kontabilitaonline.it
https://kontabilitaonline.it/login
```

Credenziali seed iniziali:

```text
superadmin@agency.test
password
```

Cambia subito email e password dell'account admin.

## 8. Aggiornamenti futuri

Quando pubblichi nuove modifiche su GitHub:

1. In cPanel Git Version Control fai **Pull or Deploy**
2. Copia eventuali nuovi file da `/kontabilit/public` a `/public_html`
3. Riapri temporaneamente `deploy-once.php` solo se ci sono nuove migration
4. Cancella di nuovo `deploy-once.php`
