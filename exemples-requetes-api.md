# Exemples de requêtes pour tester l'API F1 Infractions

## 1. Authentification

### Se connecter en tant qu'Admin

localhost:8000/api/login
{"username":"admin@f1.com","password":"admin123"}


**Réponse attendue:**
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
}


### Se connecter en tant qu'utilisateur normal

localhost:8000/api/login
{"username":"user@f1.com","password":"user123"}


## 2. Lister les infractions

### Toutes les infractions

TOKEN="exemple-token-jwt"
localhost:8000/api/infractions


### Filtrer par écurie

localhost:8000/api/infractions?ecurie=2


### Filtrer par pilote

localhost:8000/api/infractions?pilote=4"


### Filtrer par date

localhost:8000/api/infractions?date=2024-05-26


### Filtres combinés
localhost:8000/api/infractions?ecurie=2&date=2024-07-28"


## 3. Créer des infractions (ADMIN uniquement)

### Infraction avec pénalité de points pour un pilote

TOKEN_ADMIN="exemple-token-admin"

localhost:8000/api/infractions
{
    "description": "Dépassement sous drapeaux jaunes",
    "nomCourse": "Grand Prix de Monaco",
    "piloteId": 4,
    "penalitePoints": 2
}

### Infraction avec amende pour une écurie
localhost:8000/api/infractions
{
    "description": "Dépassement du budget cap",
    "nomCourse": "Saison 2024",
    "ecurieId": 3,
    "amendeEuros": 5000000
}

### Infraction avec pénalité ET amende
localhost:8000/api/infractions
{
    "description": "Contact avec un concurrent",
    "nomCourse": "Grand Prix de Silverstone",
    "piloteId": 7,
    "penalitePoints": 3,
    "amendeEuros": 10000
}

### Infraction avec date personnalisée
{
    "description": "Non-respect des limites de la piste",
    "nomCourse": "Grand Prix du Japon",
    "piloteId": 1,
    "penalitePoints": 1,
    "dateInfraction": "2024-04-07T14:30:00+00:00"
}

## 4. Modifier les pilotes d'une écurie

### Changer le statut d'un pilote
localhost:8000/api/ecuries/1/pilotes
{
    "pilotes": [
      {
        "id": 1,
        "statut": "titulaire"
      },
      {
        "id": 2,
        "statut": "reserviste"
      }
    ]
}

### Retirer un pilote d'une écurie
localhost:8000/api/ecuries/2/pilotes
{
    "pilotes": [
      {
        "id": 6,
        "action": "remove"
      }
    ]
}

## 5. Test de la suspension automatique

### Créer une infraction qui déclenche la suspension
http://localhost:8000/api/infractions
{
    "description": "Excès de vitesse en zone de sécurité",
    "nomCourse": "Grand Prix de Singapour",
    "piloteId": 2,
    "penalitePoints": 5
}

# Vérifier le statut du pilote (devrait être "suspendu" avec 7 points)
localhost:8000/api/ecuries/1/pilotes
{"pilotes":[{"id":2}]}

## 6. Tests de sécurité

### Tentative de création d'infraction sans être admin (devrait échouer)

TOKEN_USER="token-utilisateur-normal"

localhost:8000/api/infractions
{
    "description": "Test",
    "nomCourse": "Test",
    "piloteId": 1,
    "penalitePoints": 1
}


**Réponse attendue:** HTTP 403 Forbidden

### Tentative d'accès sans authentification (devrait échouer)
http://localhost:8000/api/infractions


**Réponse attendue:** HTTP 401 Unauthorized

## 7. Tests de validation

### Données invalides - pilote inexistant
http://localhost:8000/api/infractions
{
    "description": "Test",
    "nomCourse": "Test",
    "piloteId": 999,
    "penalitePoints": 1
}

**Réponse attendue:** HTTP 404 Not Found

### Données invalides - pénalité négative
http://localhost:8000/api/infractions
{
    "description": "Test",
    "nomCourse": "Test",
    "piloteId": 1,
    "penalitePoints": -5
}

**Réponse attendue:** HTTP 400 Bad Request

### Données invalides - format de date incorrect
localhost:8000/api/infractions?date=2024/05/26"


**Réponse attendue:** HTTP 400 Bad Request

## 8. IDs de référence

### Écuries
- 1: Mercedes-AMG Petronas F1 Team
- 2: Scuderia Ferrari
- 3: Oracle Red Bull Racing
- 4: BWT Alpine F1 Team

### Pilotes
- 1: Lewis Hamilton (Mercedes)
- 2: George Russell (Mercedes)
- 3: Frederik Vesti (Mercedes - réserviste)
- 4: Charles Leclerc (Ferrari)
- 5: Carlos Sainz (Ferrari)
- 6: Antonio Giovinazzi (Ferrari - réserviste)
- 7: Max Verstappen (Red Bull)
- 8: Sergio Perez (Red Bull)
- 9: Daniel Ricciardo (Red Bull - réserviste)
- 10: Pierre Gasly (Alpine)
- 11: Esteban Ocon (Alpine)
- 12: Jack Doohan (Alpine - réserviste)

## Notes

- Remplacez `votre-token-jwt` par le token obtenu après authentification
- Les logs détaillés sont disponibles dans `var/log/dev.log`
- Je met le fichier .sql à importer à la racine du projet
