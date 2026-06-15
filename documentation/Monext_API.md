# Monext Online — Documentation API

Sources officielles :
- https://docs.monext.fr/pages/viewpage.action?pageId=747145261 (modes d'intégration)
- https://docs.monext.fr/pages/viewpage.action?pageId=753086243 (CVCo ANCV)
- https://docs.monext.fr/display/DT/Paiement+Direct (paiement direct)

---

## Tarifs

- **0,9% + 0,10€** par transaction CB européenne
- **Aucun abonnement mensuel**
- TVA : 0% sur les commissions (DSP2, institution de paiement réglementée)

---

## Modes d'intégration disponibles

| Mode | Description | PCI-DSS |
|---|---|---|
| **Plugin e-Commerce** | Module clé en main (WooCommerce, etc.) | SAQ-A |
| **Page Web / Redirection V2** | Redirection vers page hébergée Monext | SAQ-A ✅ |
| **Widget (Lightbox/in-Shop)** | Formulaire injecté dans la page | SAQ-A ✅ |
| **Direct Payment (Ajax/POST)** | Collecte des données bancaires côté marchand | SAQ-D ⚠️ |

**Recommandé pour StudiMove : Mode Redirection V2 ou Widget** (SAQ-A, pas de contrainte PCI-DSS lourde)

---

## API WebPayment — Paiement standard CB

### Service principal : `doWebPayment`

**Paramètres clés :**

| Paramètre | Valeur | Description |
|---|---|---|
| `action` | `101` | Paiement à la commande |
| `mode` | `CPT` | Paiement comptant |
| `contractNumber` | string | Numéro de contrat Monext |
| `amount` | integer | Montant en centimes |
| `currency` | `978` | Euro (ISO 4217) |
| `returnURL` | URL | Redirection après paiement |
| `cancelURL` | URL | Redirection si annulation |
| `buyer.email` | string | Email de l'acheteur (obligatoire) |

### Récupérer le résultat : `getWebPaymentDetails`

Appelé après redirection sur `returnURL` pour confirmer le paiement.

**Code retour :**
- `ACCEPTED` / `00000` → paiement validé ✅
- `REFUSED` / `01xxx` → refus bancaire
- `REFUSED` / `04xxx` → fraude détectée

---

## CVCo ANCV — Chèque-Vacances Connect

### Code moyen de paiement : `ANCV_CONNECT`

StudiMove est déjà prestataire agréé ANCV ✅

### Flux de paiement CVCo

1. Appel `doWebPayment` avec :
   - `action: 101`, `mode: CPT`
   - Contrat CVCo dans `selectedContractList`
   - `buyer.email` obligatoire
2. L'utilisateur est redirigé vers l'app mobile ANCV pour valider
3. Retour sur `returnURL`
4. Appel `getWebPaymentDetails` → résultat dans `paymentAdditionalList` + objet `transaction`

### Paiement mixte CVCo + CB

Supporté nativement : spécifier les deux contrats (CVCo + CB) dans `selectedContractList`.  
La réponse contient les deux transactions séparées.

### Remboursement

Utiliser `doRefund` pour rembourser un paiement CVCo (même service que CB).

---

## SDK disponibles

- PHP
- C#
- Java
- Appels SOAP directs

---

## À récupérer avant intégration

- [ ] Compte Monext Online (administration center)
- [ ] Clé d'accès API (dans le panneau d'administration)
- [ ] Numéro de contrat CB
- [ ] Numéro de contrat CVCo ANCV
- [ ] URL sandbox de test
- [ ] Credentials de test

---

## Architecture cible dans StudiMove

- `api/payment.php` : appel `doWebPayment` avec montant + contrats
- `payment_return.php` : page `returnURL` → appelle `getWebPaymentDetails` → enregistre en Supabase
- `payment_cancel.php` : page `cancelURL` → affiche message d'annulation
- Réservation en base : créée à la confirmation (`ACCEPTED` / `00000`)
