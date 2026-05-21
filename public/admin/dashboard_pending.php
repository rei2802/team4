<?php
include '../config.php';
// ── PENDING REQUESTS QUERY (replace in dashboard.php) ──
// Joins guest_bookings so guest contact info shows correctly
$query = "SELECT b.*,
            COALESCE(b.company_name,  u.company_name,  gb.company_name)   AS display_company,
            COALESCE(b.contact_person,u.contact_person,gb.contact_person)  AS display_contact,
            COALESCE(b.contact_number,u.contact_number,gb.contact_number)  AS display_phone,
            COALESCE(b.special_transport, b.transport_type)                AS display_transport,
            (SELECT id FROM booking_confirmations WHERE booking_id = b.booking_id LIMIT 1) AS has_confirmation,
            CASE WHEN b.user_id IS NULL THEN 1 ELSE 0 END AS is_guest
          FROM bookings b
          LEFT JOIN users u        ON u.user_id      = b.user_id
          LEFT JOIN guest_bookings gb ON gb.booking_id = b.booking_id
          WHERE b.booking_status = 'Pending'
          ORDER BY b.booking_id DESC";
?>
 