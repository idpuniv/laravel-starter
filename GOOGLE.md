Tu les obtiens depuis la console Google Cloud (OAuth 2.0).

Voici la procédure exacte, étape par étape.

---

# 1. Créer un projet Google Cloud

Va sur :
[Google Cloud Console](https://console.cloud.google.com/?utm_source=chatgpt.com)

* Clique sur **Select project**
* Puis **New project**
* Donne un nom (ex: `MonApp Laravel`)
* Crée le projet

---

# 2. Activer l’API Google OAuth

Dans le menu :

* **APIs & Services → Library**
* Cherche : **Google Identity** ou **Google People API**
* Clique sur **Enable**

---

# 3. Configurer l’écran de consentement OAuth

Va dans :

* **APIs & Services → OAuth consent screen**

Choisis :

* External (pour utilisateurs classiques)

Puis remplis :

* App name : ton application
* User support email : ton email
* Developer contact email : ton email

Ensuite :

* Sauvegarde
* Ajoute les scopes de base :

  * `email`
  * `profile`

---

# 4. Créer les credentials (Client ID + Secret)

Va dans :

* **APIs & Services → Credentials**
* Clique **Create Credentials**
* Choisis **OAuth client ID**

Puis :

### Type d’application

* Web application

---

### Important : Authorized redirect URI

Ajoute exactement :

```
http://localhost:8000/auth/google/callback
```

et en production :

```
https://ton-domaine.com/auth/google/callback
```

---

# 5. Récupérer les clés

Après création, Google te donne :

* **Client ID**
* **Client Secret**

Exemple :

```env
GOOGLE_CLIENT_ID=1234567890-abc.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-xxxxxxxxxxxx
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

---

# 6. Important (erreurs fréquentes)

### Erreur redirect_uri_mismatch

→ 100% causé par une URL différente entre :

* `.env`
* Google Console

Les deux doivent être identiques à 100%

---

### En local

Utilise :

```env
http://127.0.0.1:8000/auth/google/callback
```

ou

```env
http://localhost:8000/auth/google/callback
```

mais pas les deux mélangés.

---

# 7. Résumé simple

* Google Cloud = générateur de clés
* Client ID = identifiant public
* Client Secret = mot de passe serveur
* Redirect URI = URL de retour obligatoire

---
