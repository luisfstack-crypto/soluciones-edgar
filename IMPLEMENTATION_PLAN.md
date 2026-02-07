# Implementation Plan: Wallet, Approvals, and Refunds

## 1. Database & Models
- [ ] **Create `deposit_requests` table**:
    - `user_id` (FK)
    - `bank_name` (string)
    - `tracking_key` (string, unique?) -> "Clave de rastreo"
    - `amount` (decimal)
    - `proof_image_path` (string)
    - `status` (enum: pending, approved, rejected)
    - `admin_notes` (text)
- [ ] **Create `transactions` table**:
    - `user_id` (FK)
    - `type` (enum: deposit, payment, refund)
    - `amount` (decimal)
    - `reference_type` (nullable, polymorphic)
    - `reference_id` (nullable, polymorphic)
    - `description` (string)
- [ ] **Models**: `DepositRequest`, `Transaction`.
- [ ] **User Model Update**: Add methods `credit(amount, description, reference)` and `debit(amount, description, reference)` to handle balance changes and transaction logging atomically.

## 2. Admin Logic (Filament)
- [ ] **`DepositRequestResource`**:
    - List view: Show status, user, amount, proof.
    - Actions: `Approve` (adds balance, changes status), `Reject` (changes status). Use Database Transactions.
    - Form: Read-only for most fields, editable `admin_notes`.

## 3. Client Logic (Filament)
- [ ] **"Recargar Saldo" Interface**:
    - Can be the `create` page of `DepositRequestResource` or a custom Page.
    - Fields: Bank Name, Tracking Key, Amount (min 300 validation), Proof upload.
    - Instructions text / Placeholder for "Solo Transferencias".
- [ ] **Wallet History**:
    - A view (maybe a RelationManager or a Dashboard Widget) showing the user's `transactions`.

## 4. Order Processing Updates
- [ ] **Payment Logic**: Ensure `ServiceResource` uses the new `User::debit` method.
- [ ] **Refund Logic**: In `OrderResource` status change (to `rejected`), trigger `User::credit` if it wasn't already refunded.
- [ ] **Email Notification**:
    - Create `OrderCompleted` Mailable.
    - In `OrderResource`, when status -> `completed` (via `upload_result` or edit), dispatch email with attachment.

## 5. UI/UX
- [ ] **Dashboard**: Update stats to ensure they read from the correct data.
- [ ] **Styling**: Ensure Dark Mode and Red/Amber accents.

## 6. Testing
- [ ] Verify deposit flow (User request -> Admin approve -> Balance update).
- [ ] Verify order flow (Purchase -> Balance deduction -> Admin complete -> Email).
- [ ] Verify refund flow (Admin reject -> Balance refund).
