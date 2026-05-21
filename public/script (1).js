document.addEventListener("DOMContentLoaded", function () {

  // HAMBURGER
  const hamburger = document.querySelector('.hamburger');
  const navLinks  = document.querySelector('.nav-links');
  if (hamburger) {
    hamburger.addEventListener('click', () => navLinks.classList.toggle('active'));
  }

  // TRUCK SETTINGS
  const TRUCKS = {
    "L300 Cargo Van": { label: "L300 Cargo Van",  fuelEff: 10, opFee: 1800, maxWeight: 1000  },
    "4-wheeler":      { label: "4-Wheeler Traviz", fuelEff: 8,  opFee: 2200, maxWeight: 2000  },
    "6-wheeler":      { label: "6-Wheeler",        fuelEff: 5,  opFee: 3000, maxWeight: 10000 },
  };

  const SPECIAL_SURCHARGE = {
    "None":       { label: "None",               rate: 0.00 },
    "Fragile":    { label: "Fragile Items",       rate: 0.10 },
    "Hazardous":  { label: "Hazardous Materials", rate: 0.20 },
    "Perishable": { label: "Perishable Items",    rate: 0.15 },
    "High value": { label: "High Value Goods",    rate: 0.25 },
  };

  const FREQUENCY = {
    "once":    { label: "Once",    trips: 1  },
    "daily":   { label: "Daily",   trips: 26 },
    "weekly":  { label: "Weekly",  trips: 4  },
    "monthly": { label: "Monthly", trips: 1  },
  };

  const DIESEL_PRICE   = 65;
  const VAT            = 0.12;
  const WEIGHT_RATE    = 10;
  const BASE_WEIGHT_KG = 500;

  function fmt(n) {
    return "₱" + Number(n).toLocaleString("en-PH", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
  }

  // CALCULATE ESTIMATE
  window.calculateEstimate = function () {
    const pickup      = document.getElementById("pickup").value.trim();
    const delivery    = document.getElementById("delivery").value.trim();
    const distanceRaw = parseFloat(document.getElementById("distanceKm").value);
    const truckKey    = document.getElementById("truckType").value;
    const special     = document.getElementById("specialTransport").value;
    const weight      = parseFloat(document.getElementById("cargoWeight").value) || 0;
    const weightUnit  = document.getElementById("weightUnit").value;
    const freqKey     = document.getElementById("frequency").value || "once";

    if (!pickup)                          { alert("⚠️ Please enter a Pickup Location.");               return; }
    if (!delivery)                        { alert("⚠️ Please enter a Delivery Location.");              return; }
    if (!distanceRaw || distanceRaw <= 0) { alert("⚠️ Please enter a valid one-way distance in km."); return; }
    if (!truckKey)                        { alert("⚠️ Please select a Truck Type.");                    return; }
    if (!special)                         { alert("⚠️ Please select a Specialized Transport type."); return; }
    if (weight <= 0)                      { alert("⚠️ Please enter a valid Cargo Weight.");             return; }

    const truck    = TRUCKS[truckKey];
    const specData = SPECIAL_SURCHARGE[special] || SPECIAL_SURCHARGE['None'];
    const freqData = FREQUENCY[freqKey];
    const weightKg = weightUnit === "tons" ? weight * 1000 : weight;

    if (weightKg > truck.maxWeight) {
      alert(`⚠️ ${truck.label} can only carry up to ${truck.maxWeight.toLocaleString()} kg.\nYour cargo is ${weightKg.toLocaleString()} kg.\nPlease select a bigger truck.`);
      return;
    }

    const distanceOneWay = distanceRaw;
    const distanceTwoWay = distanceRaw * 2;
    const fuelNeeded     = distanceTwoWay / truck.fuelEff;
    const fuelCost       = fuelNeeded * DIESEL_PRICE;
    const opFee          = truck.opFee;
    const excessKg       = Math.max(0, weightKg - BASE_WEIGHT_KG);
    const weightCharge   = excessKg * WEIGHT_RATE;
    const basePerTrip    = fuelCost + opFee + weightCharge;
    const specialCharge  = specData ? basePerTrip * specData.rate : 0;
    const costPerTrip    = basePerTrip + specialCharge;
    const trips          = freqData.trips;
    const subtotalAll    = costPerTrip * trips;
    const vatAmount      = subtotalAll * VAT;
    const grandTotal     = subtotalAll + vatAmount;

    document.getElementById("estPickup").textContent       = pickup;
    document.getElementById("estDelivery").textContent     = delivery;
    document.getElementById("estOneWay").textContent       = distanceOneWay.toLocaleString() + " km";
    document.getElementById("estDistance").textContent     = distanceTwoWay.toLocaleString() + " km";
    document.getElementById("estTruck").textContent        = truck.label;
    document.getElementById("estWeight").textContent       = weightKg.toLocaleString() + " kg";
    document.getElementById("estFuelNeeded").textContent   = fuelNeeded.toFixed(2) + " liters";
    document.getElementById("estFuelCost").textContent     = fmt(fuelCost);
    document.getElementById("estOpFee").textContent        = fmt(opFee);
    document.getElementById("estWeightCharge").textContent = excessKg > 0
      ? fmt(weightCharge) + ` (${excessKg.toLocaleString()} kg excess × ₱${WEIGHT_RATE})`
      : "Included (within 500 kg)";
    document.getElementById("estSpecial").textContent      = specData && specData.rate > 0
      ? fmt(specialCharge) + ` (${specData.label} — ${specData.rate * 100}%)`
      : "None";
    document.getElementById("estSubtrip").textContent      = fmt(costPerTrip);
    document.getElementById("estTrips").textContent        = trips + (trips === 1 ? " trip" : " trips") + ` (${freqData.label})`;
    document.getElementById("estSubtotal").textContent     = fmt(subtotalAll);
    document.getElementById("estVat").textContent          = fmt(vatAmount);
    document.getElementById("estTotal").textContent        = fmt(grandTotal);

    const result = document.getElementById("estimateResult");
    result.style.display = "block";
    result.scrollIntoView({ behavior: "smooth", block: "nearest" });
  };

  // FORM SUBMIT
  const form = document.getElementById("bookingForm");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const person   = document.getElementById("contactPersonName").value.trim();
      const phone    = document.getElementById("contactNumber").value.trim();
      const email    = document.getElementById("contactEmail").value.trim();
      const pickup   = document.getElementById("pickup").value.trim();
      const delivery = document.getElementById("delivery").value.trim();
      const distance = parseFloat(document.getElementById("distanceKm").value);
      const truck    = document.getElementById("truckType").value;
      const special  = document.getElementById("specialTransport").value;
      const weight   = parseFloat(document.getElementById("cargoWeight").value);

      if (!person || !phone || !email || !pickup || !delivery ||
          !truck || !special || isNaN(weight) || weight <= 0 ||
          isNaN(distance) || distance <= 0) {
        alert("⚠️ Please fill in all required fields.");
        return;
      }
      if (!email.includes("@") || !email.includes(".")) {
        alert("⚠️ Invalid email format.");
        return;
      }
      if (phone.length < 10) {
        alert("⚠️ Invalid phone number.");
        return;
      }

      fetch("submitbooking.php", {
        method: "POST",
        body: new FormData(form)
      })
      .then(response => response.text())
      .then(data => {
        const trimmed = data.trim();
        if (trimmed === "success") {
          alert(
            "✅ Booking confirmed!\n\n" +
            "Please wait for 2K2J Services to review your booking.\n" +
            "You will receive a confirmation with payment instructions shortly."
          );
          form.reset();
          document.getElementById("estimateResult").style.display = "none";
          window.location.href = "my-bookings.php";
        } else {
          alert("❌ Something went wrong: " + trimmed);
        }
      })
      .catch(error => {
        console.error(error);
        alert("❌ Submission failed. Please try again.");
      });
    });
  }

});


// Wrap it in a submit event listener so it waits for the user!
const bookingForm = document.getElementById('bookingForm');

if (bookingForm) {
    bookingForm.addEventListener('submit', function (e) {
        // 1. Stop the page from refreshing automatically
        e.preventDefault();

        // 2. Run your frontend check (from book.php) to see if they are a guest or logged in
        // If it returns false, stop right here!
        if (typeof checkBeforeSubmit === 'function' && !checkBeforeSubmit(e)) {
            return false;
        }

        // 3. Now that everything is valid, grab the form data and send it
        fetch('submitbooking.php', {
            method: 'POST',
            body: new FormData(this) // 'this' refers directly to the bookingForm
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'success') {
                alert('🎉 Booking submitted successfully!');
                window.location.href = 'my-bookings.php';
            } else if (data.includes('<!DOCTYPE html>')) {
                alert('⚠️ Session timed out or server configuration error. Please log in again.');
                window.location.href = 'user_login.php';
            } else {
                alert('❌ Error: ' + data);
            }
        })
        .catch(err => {
            console.error("Submission failed:", err);
            alert('❌ Network error. Please try again.');
        });
    });
}