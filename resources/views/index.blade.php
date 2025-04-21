<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Bid Calculator</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(120deg, #e0f7fa, #ffffff);
      min-height: 100vh;
    }
    .card {
      border-radius: 20px;
      box-shadow: 0 5px 30px rgba(0,0,0,0.1);
    }
    .form-select, .form-control {
      border-radius: 10px;
    }
    .fee-label {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div id="app" class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card p-4">
          <h2 class="text-center mb-4">🚗 Vehicle Bid Calculator</h2>
          <form @submit.prevent>
            <div class="mb-3">
              <label class="form-label">Vehicle Price ($)</label>
              <input type="number" class="form-control" v-model.number="price" min="1" />
            </div>
            <div class="mb-4">
              <label class="form-label">Vehicle Type</label>
              <select class="form-select" v-model="type">
                <option value="">Select type</option>
                <option value="common">Common</option>
                <option value="luxury">Luxury</option>
              </select>
            </div>
          </form>

          <div v-if="result" class="mt-4">
            <h5 class="text-center">📋 Fees Breakdown</h5>
            <ul class="list-group mb-3">
              <li class="list-group-item d-flex justify-content-between">
                <span class="fee-label">Basic Buyer Fee</span> <span>$[[ result.fees.basic_buyer_fee ]]</span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="fee-label">Special Fee</span> <span>$[[ result.fees.special_fee ]]</span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="fee-label">Association Fee</span> <span>$[[ result.fees.association_fee ]]</span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span class="fee-label">Storage Fee</span> <span>$[[ result.fees.storage_fee ]]</span>
              </li>
            </ul>
            <div class="text-center fs-4">
              💰 <strong>Total: $[[ result.total ]]</strong>
            </div>
          </div>

          <div v-if="error" class="alert alert-danger mt-3 text-center">[[ error ]]</div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/vue@3.4.21/dist/vue.global.prod.js"></script>
  <script>
    const { createApp, watch } = Vue;

    const app = createApp({
      data() {
        return {
          price: null,
          type: '',
          result: null,
          error: null,
          timeout: null
        };
      },
      watch: {
        price: 'calculate',
        type: 'calculate'
      },
      methods: {
        calculate() {
          this.error = null;
          this.result = null;

          if (!this.price || !this.type) return;

          clearTimeout(this.timeout);

          this.timeout = setTimeout(async () => {
            try {
              const res = await fetch('/api/calculate-bid', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                  vehicle_price: this.price,
                  vehicle_type: this.type
                })
              });

              if (!res.ok) throw new Error("Server error");

              const data = await res.json();
              this.result = data;
            } catch (e) {
              this.error = 'Invalid input or server error';
            }
          }, 400);
        }
      }
    });

    app.config.compilerOptions.delimiters = ['[[', ']]'];
    app.mount('#app');
  </script>
</body>
</html>
