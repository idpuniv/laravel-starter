
# Contribuer au projet

Merci de prendre le temps de contribuer à ce projet.  
Ce projet suit un workflow structuré inspiré des bonnes pratiques Laravel.

---

## 1. Comment contribuer

Les contributions se font via des Merge Requests (Pull Requests).

Avant de soumettre une contribution, assurez-vous que :

- Le code est clair et compréhensible
- Les modifications sont testées localement
- Les standards du projet sont respectés
- Le code suit **les conventions déjà en place dans la codebase existante**
- Vous avez relu votre code avant soumission

### Règle importante

Avant de créer un nouveau composant (controller, service, request, etc.) :

- Analysez le code existant
- Respectez **le même style et la même structure déjà utilisés**
- Ne pas introduire de nouveau pattern sans validation de l’équipe
- Reproduire les conventions déjà appliquées dans le projet

Exemples :
- Si un controller existant utilise des Services → vous devez aussi utiliser des Services
- Si un pattern de validation existe → vous devez le réutiliser
- Si une structure de dossier existe → vous devez la suivre

---

## 2. Signalement de bugs

Les rapports de bugs sont les bienvenus, mais les contributions via MR sont privilégiées.

Lors d’un signalement, merci d’inclure :

- Une description claire du problème
- Les étapes pour reproduire le bug
- Le comportement attendu vs observé
- Des captures d’écran ou extraits de code si nécessaire

---

## 3. Proposition de fonctionnalités

Les nouvelles fonctionnalités doivent idéalement être discutées avant implémentation.

Utilisez :

- Issues / Work Items
- Discussions du projet (si disponibles)

Une fonctionnalité doit :

- Être alignée avec les objectifs du projet
- Éviter une complexité inutile
- Rester compatible avec l’existant (sauf indication contraire)

---

## 4. Stratégie de branches

Le projet suit une stratégie inspirée de Git Flow :

| Branche       | Rôle |
|--------------|------|
| `main`       | Code en production stable |
| `develop`    | Branche d’intégration |
| `feature/*`  | Nouvelles fonctionnalités |
| `hotfix/*`   | Corrections urgentes |
| `release/*`  | Préparation des versions |

### Règles importantes

- Toute nouvelle fonctionnalité part de `develop`
- Les hotfix partent de `main`
- Les Merge Requests doivent viser `develop` (sauf hotfix → main)

---

## 5. Git Workflow

Le détail complet du workflow Git (branches, synchronisation, rebase, commits, bonnes pratiques) est défini dans le fichier :

[GIT.md](./docs/GIT.md)

Ce fichier doit être consulté avant toute contribution.

---

## 6. Merge Requests (MR)

Avant de soumettre une MR :

- Le code doit être testé et fonctionnel
- Le code doit respecter les conventions du projet
- Une description claire doit être fournie
- L’issue liée doit être mentionnée si elle existe

### Checklist MR

- [ ] Code sans erreur de compilation
- [ ] Fonctionnalité testée localement
- [ ] Aucun debug (`dd`, `dump`, logs inutiles`)
- [ ] Documentation mise à jour si nécessaire

---

## 7. Standards de code

Ce projet suit **PSR-12** et les conventions Laravel.

### Règles principales

- Utiliser des noms explicites
- Garder des méthodes courtes et ciblées
- Éviter la duplication (principe DRY)
- Valider systématiquement les entrées utilisateur
- Respecter strictement **la structure et le style du code existant**

---

### Principe fondamental

> Le code nouveau doit toujours s’aligner sur le code existant.

Avant d’écrire :

- Controller → observer les controllers existants
- Service → suivre la structure des services existants
- Request → réutiliser le pattern de validation déjà utilisé
- Route → respecter la structure actuelle des routes
- etc

Interdit :
- Introduire un nouveau style sans justification
- Modifier une architecture existante sans validation

---

## 8. Convention de commits

Nous utilisons les commits conventionnels :

```

type(scope): description

```

### Types

| Type      | Description |
|-----------|------------|
| feat      | Nouvelle fonctionnalité |
| fix       | Correction de bug |
| refactor  | Refactorisation du code |
| docs      | Documentation |
| style     | Mise en forme uniquement |
| test      | Tests |
| chore     | Maintenance |

### Exemples

```

feat(auth): ajout de l’authentification par OTP
fix(cart): correction du calcul des prix
docs(readme): mise à jour de l’installation

```

---

## 9. Contributions assistées par IA

Les contributions assistées par IA sont acceptées, mais :

- Vous devez comprendre entièrement le code soumis
- Vous devez tester et valider le résultat
- Vous êtes responsable de la qualité finale

Les contributions non vérifiées ne sont pas acceptées.

---

## 10. Résumé du workflow

1. Créer une branche depuis `develop`
2. Développer la fonctionnalité ou correction
3. Commiter avec la convention définie
4. Pousser la branche
5. Ouvrir une Merge Request
6. Attendre la revue et la CI
7. Fusion après validation

---

Merci pour votre contribution au projet.

