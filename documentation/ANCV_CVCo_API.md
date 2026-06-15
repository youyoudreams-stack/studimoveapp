# ANCV Chèques Vacances Connect (CVCo) — Documentation API

Sources officielles :
- https://docs.direct.worldline-solutions.com/en/payment-methods-and-features/payment-methods/cheque-vacances-connect
- https://static.ancv.com/ddmc/connect/spectechniques/Kit_Integration_par_API_du_CVCo.pdf

---

## Vue d'ensemble

CVCo est la version 100% numérique du Chèque-Vacances ANCV. L'utilisateur paie via l'app mobile ANCV. StudiMove est déjà prestataire agréé ANCV.

---

## Deux modes d'intégration

### 1. Hosted Checkout (recommandé pour démarrer)
- Redirection vers le portail ANCV
- ANCV gère toute la UI de paiement
- Retour sur `returnUrl` après confirmation
- Méthode : `CreateHostedCheckout`

### 2. Server-to-server (plus de contrôle)
- Intégration directe dans le tunnel de paiement StudiMove
- Requiert le `beneficiaryId` de l'utilisateur (trouvé dans son app ANCV)
- Méthode : `CreatePayment`

---

## Paramètres clés

| Paramètre | Valeur | Description |
|---|---|---|
| `paymentProductId` | `5412` | Identifiant produit CVCo |
| `beneficiaryId` | string | ID ANCV de l'utilisateur (mode server-to-server) |
| `requiresApproval` | `false` | Obligatoire pour paiement mixte CVCo + CB |
| `adjustableAmount` | `true/false` | L'utilisateur peut ajuster le montant CVCo |
| `returnUrl` | URL | Redirection après paiement |

---

## Flux de paiement

1. L'utilisateur choisit de payer avec CVCo
2. Requête `CreatePayment` ou `CreateHostedCheckout` envoyée avec le montant
3. L'app ANCV s'ouvre sur le mobile de l'utilisateur
4. L'utilisateur confirme (+ paiement complémentaire CB si besoin)
5. Confirmation reçue via webhook ou redirection `returnUrl`
6. Statut final : `status="CAPTURED"` + `statusOutput.statusCode=9` = paiement OK

---

## Split payment (CVCo + CB)

Le paiement mixte est supporté nativement — l'utilisateur peut payer une partie en CVCo et le reste en CB. Idéal pour les offres voyages StudiMove.

---

## Codes de réponse

| Statut | Code | Signification |
|---|---|---|
| `CAPTURED` | `9` | Paiement complété avec succès |

---

## Webhooks

Utiliser les webhooks Worldline pour recevoir les résultats transactionnels asynchrones.  
Méthode de vérification : `GetPaymentDetails`

---

## Environnement

- **Sandbox** : environnement de test fourni par Worldline (credentials séparés)
- **Production** : credentials ANCV prestataire agréé

---

## À récupérer avant intégration

- [ ] Credentials API (clé API + identifiant prestataire ANCV)
- [ ] Savoir si on passe par Worldline ou directement l'API ANCV
- [ ] URL sandbox de test
- [ ] Choix du mode : Hosted Checkout vs Server-to-server

---

## Architecture cible dans StudiMove

- Côté PHP (`api/payment.php`) : appel `CreatePayment` avec montant + `beneficiaryId`
- Page de confirmation : `returnUrl` vers une page de succès dans l'app
- Webhook : endpoint PHP pour recevoir et enregistrer la confirmation en base Supabase
