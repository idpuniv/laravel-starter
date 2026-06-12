

# Git Workflow & Standards

Ce document définit les règles Git du projet : stratégie de branches, synchronisation, commits et bonnes pratiques.

---

## 1. Stratégie de branches

Le projet suit une approche inspirée de Git Flow.

| Branche       | Rôle |
|--------------|------|
| `main`       | Code en production stable |
| `develop`    | Branche d’intégration |
| `feature/*`  | Développement de nouvelles fonctionnalités |
| `hotfix/*`   | Corrections urgentes en production |
| `release/*`  | Préparation des versions |

---

### Règles de base

- Toute nouvelle fonctionnalité doit partir de `develop`
- Les hotfix partent de `main`
- Les Merge Requests doivent viser `develop` (sauf hotfix → main)

---

## 2. Cycle de développement standard

### Création d’une feature

```bash id="g1c8qz"
git checkout develop
git pull origin develop
git checkout -b feature/ma-fonctionnalite
````

---

### Développement

```bash id="d9k2lm"
git add .
git commit -m "feat(scope): description claire"
```

---

### Envoi de la branche

```bash id="p8r2wx"
git push origin feature/ma-fonctionnalite
```

---

## 3. Synchronisation avec `develop`

### Règle importante

Une branche feature doit rester synchronisée avec `develop`.

---

### Quand synchroniser ?

* Au début de chaque journée
* Avant un commit important
* Avant d’ouvrir une Merge Request
* Lors de conflits ou changements majeurs sur `develop`

---

### Méthode recommandée (rebase)

```bash id="r3n9qa"
git fetch origin
git rebase origin/develop
```

---

### Alternative (merge)

```bash id="m7v2sd"
git fetch origin
git merge origin/develop
```

---

### Gestion des conflits

```bash id="c1f8lp"
# résoudre les conflits manuellement
git add .
git rebase --continue
```

---

## 4. Merge Request (MR)

Avant de créer une MR :

* La branche doit être à jour avec `develop`
* Le code doit être testé localement
* Aucun code de debug (`dd`, `dump`, logs inutiles`)
* Les conventions du projet doivent être respectées

---

## 5. Convention de commits

### Format obligatoire

````
type(scope): description
``` id="cmt1"

---

### Types autorisés

| Type | Description |
|------|------------|
| feat | Nouvelle fonctionnalité |
| fix | Correction de bug |
| refactor | Refactorisation |
| docs | Documentation |
| style | Formatage uniquement |
| test | Ajout de tests |
| chore | Maintenance |

---

### Exemples

```bash id="cmt2"
feat(auth): add login system
fix(cart): correct price calculation
docs(readme): update installation guide
````

---

## 6. Bonnes pratiques Git

* Ne jamais travailler directement sur `main`
* Ne jamais pousser sur `develop` sans MR
* Toujours synchroniser avant une MR
* Garder les branches courtes et ciblées
* Supprimer les branches fusionnées

---

## 7. Règles de qualité

* Une branche = une fonctionnalité
* Un commit = une action logique
* Une MR = un objectif clair

---

## 8. Résumé du workflow

```text id="flow1"
develop
   ↓
feature/* (développement)
   ↓
sync régulière avec develop
   ↓
MR vers develop
   ↓
review + CI
   ↓
merge
```

---

## 9. Recommandation importante

Toujours garder sa branche feature proche de `develop` afin de :

* réduire les conflits
* faciliter les Merge Requests
* garantir la stabilité du projet


