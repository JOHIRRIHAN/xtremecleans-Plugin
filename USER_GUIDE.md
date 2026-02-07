# XtremeCleans Plugin – How to Use (User Guide)

This guide explains how to install, configure, and use the XtremeCleans WordPress plugin so customers can book services and you can manage orders and sync with Jobber.

---

## 1. Installation

1. Upload the `xtremecleans` folder to your WordPress **`wp-content/plugins/`** directory (or install via WordPress admin if you have a zip).
2. In WordPress go to **Plugins** and **Activate** “XtremeCleans”.
3. You will see a new menu **XtremeCleans** in the admin sidebar.

---

## 2. Showing the Booking Form on Your Site

The main booking form (ZIP → services → appointment → customer info → payment) is displayed with the shortcode:

```
[xtremecleans]
```

**Where to add it:**

- **Option A – Page:** Create a new **Page** (e.g. “Book Now” or “Get a Quote”). Add the shortcode `[xtremecleans]` in the content. Publish the page. Customers open this page to book.
- **Option B – Elementor:** If you use Elementor, add the **XtremeCleans** widget where you want the form (e.g. in a full-width section). The widget outputs the same booking form.

No other shortcode is required for the full booking flow. The form includes:

1. **ZIP code** – Customer enters ZIP; the plugin checks if the area is in a zone you defined.
2. **Service selection** – Customer picks services and quantities; quote updates automatically.
3. **Appointment date & arrival window** – Calendar with time slots (e.g. 8–9 AM, 11 AM–2 PM, 2:30–5 PM). Already-booked slots are shown as unavailable.
4. **Your information** – First name, last name, email, phone, alternate phone, address, city, state, ZIP, special instructions.
5. **Place order** – Submits the order. If Stripe is enabled, a deposit payment step appears; after payment, the order is saved and synced to Jobber.

---

## 3. Admin Menu – What Each Section Does

After activation, use the **XtremeCleans** menu in the WordPress admin.

| Menu item       | What it does |
|-----------------|--------------|
| **Dashboard**   | Overview and quick links to other sections. |
| **Zip Zone**    | Define service areas by ZIP code. Add zones, set service fees, and map ZIPs so the booking form knows if a customer’s ZIP is serviced and what fee to apply. |
| **Service Items** | Manage the services customers can select (e.g. carpet cleaning, upholstery). Add service names, items, types, and pricing so they appear in the booking form and in quotes. |
| **Orders**      | View all orders from the booking form. See customer details, appointment date/time, services, totals, and Jobber sync status. Use **“Push to Jobber”** to retry syncing failed or pending orders. |
| **Leads**       | When a customer enters a ZIP that is not in any zone, they can submit their details as a lead. Those entries appear here. |
| **Settings**    | Configure Jobber (OAuth Client ID/Secret, authorize connection), Stripe (enable/disable, API keys), email, and other options. |
| **Shortcodes**  | Reference list of shortcodes and how to use them. |
| **API Test**    | Test API/Jobber connection and debug if needed. |

---

## 4. Configuration (Before Going Live)

### Step 1 – Zip Zones

1. Go to **XtremeCleans → Zip Zone**.
2. Add one or more **zones** (e.g. “Downtown”, “North Area”).
3. For each zone, add the **ZIP codes** you serve and set the **service fee** (or service charge) for that zone.
4. Save. The booking form will only allow booking for ZIPs that belong to a zone and will use the correct fee.

### Step 2 – Service Items

1. Go to **XtremeCleans → Service Items**.
2. Add **services** (e.g. “Carpet Cleaning”, “Upholstery”).
3. Under each service, add **items** (e.g. “Living Room”, “Sofa”) with **types** (e.g. Clean, Protect) and **pricing**.
4. These appear in the booking form dropdowns and drive the quote and order total.

### Step 3 – Jobber (Optional but Recommended)

1. Go to **XtremeCleans → Settings** and open the **Jobber** tab.
2. Enter your **Client ID** and **Client Secret** from Jobber (API/OAuth app).
3. Click **“Authorize Jobber Connection Now”** and complete the login in Jobber so the plugin can create Clients, Quotes, and Jobs.
4. After authorization, new orders (and “Push to Jobber” on existing orders) will create a Client, Quote, and Job in your Jobber account. Customer name, email, phone, address, and special instructions are mapped as described in **JOBBER_FIELD_MAPPING.md**.

### Step 4 – Stripe (Optional)

1. In **Settings**, open the **Stripe** (or Payment) section.
2. Enable Stripe and enter your **Publishable key** and **Secret key**.
3. When enabled, customers pay a deposit (e.g. $20) after placing the order; the order is then synced to Jobber. When Stripe is disabled, orders are saved and synced to Jobber without payment.

### Step 5 – Email (Optional)

1. In **Settings**, set the **admin email** (and any other email options) so you receive notifications for new orders or leads if the plugin is configured to send them.

---

## 5. How Customers Use the Form

1. Customer opens the **page (or Elementor section)** where you added `[xtremecleans]`.
2. Enters **ZIP code** and continues. If the ZIP is not in a zone, they may see a lead form to leave their details.
3. Selects **services and quantities**; the quote updates as they select.
4. Chooses **appointment date** and **arrival window** (e.g. 11 AM–2 PM). Already-booked slots are not clickable.
5. Fills in **name, email, phone, address, city, state, ZIP, and special instructions**.
6. Clicks **Place Order**. If Stripe is on, they pay the deposit; then they see a success message.
7. You see the order in **XtremeCleans → Orders** and (if Jobber is set up) the client, quote, and job in Jobber.

---

## 6. Managing Orders

- **View orders:** **XtremeCleans → Orders**. You see customer info, appointment, services, total, and Jobber sync status (Synced / Failed / Pending).
- **View details:** Click the **eye** (View) button on an order to see full details.
- **Sync to Jobber:** If an order shows **Failed** or **Pending**, click **“Push to Jobber”** to try again. Ensure Jobber is authorized in Settings first.
- **Export:** Use the export option (if available) to download orders for reporting.

---

## 7. Shortcode Options (Optional)

The main shortcode supports optional attributes to change labels and placeholders. Example:

```
[xtremecleans 
  zip_placeholder="Enter your ZIP" 
  continue_btn="CHECK AVAILABILITY"
  service_title="CHOOSE YOUR SERVICES"
]
```

You can customize hero image, form labels, and button text via these attributes. Full list is in **XtremeCleans → Shortcodes** in the admin.

---

## 8. Troubleshooting

- **Booking form does not appear:** Ensure the page contains `[xtremecleans]` and the plugin is active. Clear cache if you use a caching plugin.
- **ZIP not recognized:** Add that ZIP to a zone under **XtremeCleans → Zip Zone**.
- **No time slots available:** Check that the calendar is loading (no JavaScript errors) and that you have defined arrival windows in the code (defaults are 8–9 AM, 11 AM–2 PM, 2:30–5 PM). Already-booked slots are hidden automatically.
- **Order not in Jobber:** Go to **Settings → Jobber**, ensure Client ID and Secret are set and you have clicked **“Authorize Jobber Connection Now”**. Then use **“Push to Jobber”** on the order. If it still fails, check the error message in the Orders table (Jobber column).

---

## 9. Summary

| Task | Where to do it |
|------|----------------|
| Show booking form | Add `[xtremecleans]` to a page or use the Elementor widget |
| Set service areas | XtremeCleans → Zip Zone |
| Set services & pricing | XtremeCleans → Service Items |
| Connect Jobber | XtremeCleans → Settings → Jobber tab → Authorize |
| Enable payments | XtremeCleans → Settings → Stripe |
| View & sync orders | XtremeCleans → Orders |
| See shortcode reference | XtremeCleans → Shortcodes |

For where each form field (name, email, address, instructions) appears in Jobber, see **JOBBER_FIELD_MAPPING.md**.
