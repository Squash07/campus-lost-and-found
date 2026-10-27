# Manual testing guide

Use this with the demo data to click through every feature before taking report screenshots.

## Setup (Windows, run once)

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

If `migrate:fresh` asks to create the SQLite database file, answer **yes**. Then open http://127.0.0.1:8000. To reset everything back to the demo data at any time, run `php artisan migrate:fresh --seed` again.

The seeder also copies the demo item photos into `public/uploads/items`.

## Demo accounts

All passwords are `password`. On the login page you can click an account to fill the form.

| Account | Email | Use it to test |
| --- | --- | --- |
| Campus Admin | admin@lostfound.test | Category management, deleting anyone's post |
| Budi Santoso | budi@lostfound.test | 2 claims waiting for review (on his AirPods post), a response he sent on Siti's wallet, a returned backpack |
| Siti Rahma | siti@lostfound.test | 1 response waiting for review (on her lost wallet), a claimed item ready to mark returned |
| Andi Pratama | andi@lostfound.test | 1 claim waiting for review (calculator), a claimed key ready to mark returned, a rejected response |
| Dewi Lestari | dewi@lostfound.test | Claims in every state: pending, approved and rejected |

## What the demo data contains

- 15 items: 10 open, 2 claimed, 3 returned; lost and found; 12 with photos and 3 without.
- 11 claims: 4 pending, 5 approved and 2 rejected.
- 7 categories. "Umbrellas" has no items, so the admin can delete it. The others are protected.

## Checklist

### Guest (not logged in)

- [ ] F2 Feed shows item cards with Lost/Found tags, status badges and photos.
- [ ] F6 Search "wallet" in the hero search box. Then use the All / Lost / Found tabs, the category and status dropdowns. Remove a filter with its × chip, then "Clear all".
- [ ] F6 Search "zzzz" and check the empty state.
- [ ] F3 Open any item. You should see "Log in" instead of a claim form.
- [ ] Open `/items/99999` and check the styled 404 page.
- [ ] Register a new account, then log out. (Auth)

### Log in as Andi

- [ ] F1 Click "Post an item". Pick "I found something", fill the form, drag a photo onto the upload area, and post it. A toast should say "Item posted".
- [ ] F1 Try posting with an empty title and a date in the future: errors should appear under the fields, and your input should be kept.
- [ ] F4 Edit the item you just posted and replace the photo.
- [ ] F7 Open Budi's "AirPods Pro 2" post. You already sent a claim there, so you should see "Your claim: Pending".
- [ ] F7 Open Budi's "Logitech MX Master 3S mouse" (a lost item). The form should say "I found this". Try sending a 5-character message to see the counter and the error, then send a proper one.
- [ ] F8 Go to the Dashboard. "Needs your review" shows Dewi's claim on the calculator. Approve it in the confirm dialog. The calculator should now be "Claimed" and show Dewi's contact.
- [ ] F9 Open "Honda Vario key" (claimed). Click "Mark as returned" and confirm. The timeline should reach step 3.
- [ ] F5 Delete the item you posted earlier. The confirm dialog should appear first.
- [ ] Try opening `/items/1/edit` (Budi's item). You should get the styled 403 page.
- [ ] Try `/admin/categories`. You should get 403.

### Log in as Budi

- [ ] Navbar shows a red badge on Dashboard with the number of claims to review.
- [ ] F8 Open "AirPods Pro 2". Two claims are pending (Siti and Andi, plus anything you sent). Decline one, then approve Siti's. The other pending ones are declined automatically.
- [ ] F11 Dashboard: check the counts and switch between "My posts" and "Claims I sent".

### Log in as Admin

- [ ] F10 Go to Categories. Add "Lab Equipment", rename it, then delete it.
- [ ] F10 Delete "Umbrellas" (works). "Electronics" shows a greyed-out Delete because it has items.
- [ ] F5 Open any user's item. The admin sees "Delete (admin)".

### Phone layout

- [ ] Resize the browser to phone width (or use DevTools device mode): the menu collapses, cards stack in one column, and forms stay usable.
