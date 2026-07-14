<x-guest-layout>

<style>
  .btn-md-full {
    width: 100%;
    border-radius: 15px;
  }
  @media (min-width: 768px) {
    .btn-md-full {
      width: auto;
      border-radius: var(--bs-border-radius); /* valeur par défaut de Bootstrap */
    }
  }
</style>
  <div class="container">
    
  <section class="mb-3">
    <form class="mb-3">
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" class="form-control" id="exampleInputPassword1">
      </div>
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1">Check me out</label>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>


    <div class="card">
      <div class="card-body">
        <form>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
          </div>
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1">
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </section>


    <section>
      <!-- modal to offcanvas -->
      <button type="button" class="btn btn-primary" data-offcanvas-trigger="#demoModal">
        <i class="bi bi-box-arrow-up-right me-1"></i>
        Ouvrir le panneau
      </button>

      <div class="modal fade" id="demoModal"
        data-offcanvas-breakpoint="md"
        data-offcanvas="offcanvas-bottom rounded-top-4"
        data-offcanvas-height="auto"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Formulaire de test</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" class="form-control" placeholder="Votre nom" />
              </div>
              <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea class="form-control" rows="3" placeholder="Votre message"></textarea>
              </div>
               <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea class="form-control" rows="3" placeholder="Votre message"></textarea>
              </div>
               <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea class="form-control" rows="3" placeholder="Votre message"></textarea>
              </div>
               <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea class="form-control" rows="3" placeholder="Votre message"></textarea>
              </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
              <button type="button" class="btn btn-outline-secondary d-none d-md-block" data-bs-dismiss="modal">Annuler</button>
              <button type="button" class="btn btn-success btn-md-full">Valider</button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

</x-guest-layout>