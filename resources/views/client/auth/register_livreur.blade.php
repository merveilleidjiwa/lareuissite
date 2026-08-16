<?php
if (!isset($is_connected)) $is_connected = false;
if (!isset($user_nom)) $user_nom = '';
if (!isset($initiale)) $initiale = '';
if (!isset($erreur)) $erreur = '';
if (!isset($message)) $message = '';
if (!isset($success)) $success = '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>La Réussite Agronomique — Connexion</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Grand+Hotel&family=Baloo+2:wght@600;700;800&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
  :root{
    --green-1:#2E9E5B;
    --green-2:#1B6B3C;
    --green-deep:#0F4A28;
    --orange:#F2994A;
    --ink:#1B2B22;
    --ink-soft:#6E7A70;
    --line:#E4EFE8;
  }

  *{box-sizing:border-box;}
  html,body{margin:0;padding:0;}
  body{
    font-family:'Work Sans', sans-serif;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:36px 16px;
    background: radial-gradient(circle at 15% 10%, rgba(255,255,255,0.10), transparent 45%),
                radial-gradient(circle at 90% 85%, rgba(0,0,0,0.15), transparent 45%),
                linear-gradient(160deg, #27ae60 0%, #1e8449 100%);
    overflow-y: auto; /* Permet le défilement */
  }

  /* Cercles de fond du site */
  .bg-circles {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0; pointer-events: none; z-index: -1; opacity: 0.2;
  }
  .bg-circle-1 { position: absolute; top: -10%; left: -5%; width: 400px; height: 400px; border-radius: 50%; background: white; filter: blur(60px); }
  .bg-circle-2 { position: absolute; bottom: -10%; right: -5%; width: 500px; height: 500px; border-radius: 50%; background: white; filter: blur(60px); }

  .page-container{
    width:100%;
    max-width:480px;
    display:flex;
    flex-direction:column;
    align-items:center;
    margin: auto 0;
    z-index: 10;
  }

  /* -------- Logo header -------- */
  .brand{
    display:flex;
    flex-direction:column;
    align-items:center;
    margin-bottom:26px;
    text-align:center;
    text-decoration: none;
  }
  .badge{
    width:78px;height:78px;
    border-radius:50%;
    background:#fff;
    border:3px solid rgba(255,255,255,0.85);
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 10px 26px -8px rgba(0,0,0,0.35);
    margin-bottom:14px;
    overflow: hidden;
  }
  .badge img{ width: 100%; height: 100%; object-fit: cover; }
  .brand h1{
    font-family:'Grand Hotel', cursive; /* Remplacé par la font du site si besoin, mais celle-ci est bien */
    font-weight:700;
    font-size:36px;
    color:#fff;
    margin:0;
    letter-spacing:0.01em;
    line-height: 1;
  }
  .brand .sub{
    font-family:'Work Sans', sans-serif;
    font-weight:900;
    font-size:11px;
    letter-spacing:0.3em;
    color:var(--orange);
    margin-top:4px;
  }

  /* -------- Card -------- */
  .card{
    width:100%;
    background:#fff;
    border-radius:24px;
    padding:34px 30px 30px;
    box-shadow:0 30px 70px -25px rgba(0,0,0,0.45);
  }

  .tabs{
    display:flex;
    background:#F0F5F1;
    border-radius:999px;
    padding:4px;
    margin:0 auto 24px;
    width:fit-content;
  }
  .tab{
    border:none;
    background:transparent;
    font-family:'Work Sans', sans-serif;
    font-weight:600;
    font-size:13.5px;
    color:var(--ink-soft);
    padding:9px 22px;
    border-radius:999px;
    cursor:pointer;
    transition:all .25s ease;
    text-decoration: none;
    display: inline-block;
  }
  .tab.active{
    background:#27ae60;
    color:#fff;
    box-shadow:0 6px 16px -6px rgba(39, 174, 96, 0.5);
  }

  .card-head{ text-align:center; margin-bottom:24px; }
  h2.title{
    font-family:'Grand Hotel', cursive;
    font-weight:700;
    font-size:24px;
    margin:0 0 6px;
    color:var(--ink);
  }
  p.subtitle{
    font-size:13.5px;
    color:var(--ink-soft);
    margin:0;
  }

  form{ display:none; flex-direction:column; gap:14px; }
  form.active{ display:flex; }

  .row2{ display:grid; grid-template-columns:1fr 1fr; gap:12px; }

  .field{ position:relative; }
  .field input{
    width:100%;
    padding:14px 14px 14px 44px;
    border-radius:13px;
    border:1.5px solid var(--line);
    background:#FAFCFB;
    font-family:'Work Sans', sans-serif;
    font-size:14.5px;
    color:var(--ink);
    outline:none;
    text-align:left;
    transition:border-color .2s ease, box-shadow .2s ease, background .2s ease;
  }
  .field input::placeholder{ color:#9AA79E; font-weight:500; }
  .field input:focus{
    border-color:#27ae60;
    background:#fff;
    box-shadow:0 0 0 4px rgba(39, 174, 96, 0.15);
  }
  .field .icon{
    position:absolute;
    left:15px; top:50%; transform:translateY(-50%);
    width:17px;height:17px;
    color:#27ae60;
  }
  .field .eye{
    position:absolute;
    right:14px; top:50%; transform:translateY(-50%);
    cursor:pointer;
    color:#9AA79E;
    width:18px; height:18px;
    display: flex; align-items: center; justify-content: center;
  }

  .row-between{
    display:flex;
    align-items:center;
    justify-content:space-between;
    font-size:12.5px;
    margin-top:-2px;
  }
  .row-between a{ color:#27ae60; font-weight:600; text-decoration:none; }
  .remember{ display:flex; align-items:center; gap:7px; color:var(--ink-soft); }
  .remember input{ accent-color:#27ae60; width:14px; height:14px; }

  .terms{ font-size:12px; color:var(--ink-soft); line-height:1.5; text-align:center; }
  .terms a{ color:#27ae60; font-weight:600; text-decoration:none; }

  .btn-primary{
    margin-top:4px;
    border:none;
    border-radius:13px;
    padding:14px;
    background:#27ae60;
    color:#fff;
    font-family:'Work Sans', sans-serif;
    font-weight:600;
    font-size:15px;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    transition:transform .15s ease, background .2s ease;
  }
  .btn-primary:hover{ background:#1e8449; transform:translateY(-1px); }
  .btn-primary svg{ width:16px; height:16px; }

  .divider{
    display:flex;
    align-items:center;
    gap:12px;
    margin:20px 0 16px;
    color:#B7C2BA;
    font-size:11.5px;
    letter-spacing:0.06em;
    text-transform:uppercase;
  }
  .divider::before, .divider::after{
    content:"";
    flex:1;
    height:1px;
    background:var(--line);
  }

  .btn-google{
    width:100%;
    border:1.5px solid var(--line);
    background:#fff;
    border-radius:13px;
    padding:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    font-family:'Work Sans', sans-serif;
    font-weight:600;
    font-size:14px;
    color:var(--ink);
    cursor:pointer;
    transition:border-color .2s ease, background .2s ease;
  }
  .btn-google:hover{ border-color:#27ae60; background:#FAFCFB; }

  .switch-line{
    text-align:center;
    font-size:13px;
    color:var(--ink-soft);
    margin-top:22px;
  }
  .switch-line a{ color:#27ae60; font-weight:700; text-decoration:none; cursor:pointer; }

  .hint{
    font-size:11px;
    color:#fff;
    opacity: 0.7;
    text-align:center;
    margin-top:18px;
  }

  /* -------- Mobile -------- */
  @media (max-width:480px){
    body{ padding:20px 12px; }
    .page-container{ max-width:100%; }
    .badge{ width:64px; height:64px; }
    .brand h1{ font-size:26px; }
    .brand .sub{ font-size:10px; letter-spacing:0.2em; }
    .card{ padding:26px 20px 22px; border-radius:20px; }
    .row2{ grid-template-columns:1fr; }
    h2.title{ font-size:21px; }
    .field input{ padding:13px 13px 13px 40px; font-size:14px; }
    .tab{ padding:8px 16px; font-size:12.5px; }
    .row-between{ flex-direction:column; align-items:flex-start; gap:8px; }
  }
</style>
</head>
<body>

<div class="bg-circles">
    <div class="bg-circle-1"></div>
    <div class="bg-circle-2"></div>
</div>

<div class="page-container">

  <a href="{{ url('/') }}" class="brand">
    <div class="badge">
      <img src="/logo-reussite.png" alt="Logo">
    </div>
    <h1>La Réussite</h1>
    <div class="sub">AGRONOMIQUE</div>
  </a>

  <div class="card">

    <div class="tabs">
      <a href="{{ url('connexion') }}" class="tab ">Se connecter</a>
      <a href="{{ url('inscription') }}" class="tab ">S'inscrire</a>
    </div>

    <!-- LOGIN -->
    <form id="form-login" class="" method="POST" action="">
      <div class="card-head">
        <h2 class="title">Bon retour parmi nous</h2>
        <p class="subtitle">Connectez-vous pour retrouver vos paniers bio.</p>
      </div>

      <?php if(!empty($erreur)): ?>
          <div style="background:#FEF2F2; color:#B91C1C; padding:12px; border-radius:8px; font-size:13px; text-align:center; margin-bottom:10px; border:1px solid #FCA5A5;">
              <?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?>
          </div>
      <?php endif; ?>

      <div class="field">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>
        <input type="email" name="email" required placeholder="votre@email.com">
      </div>

      <div class="field">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
        <input type="password" name="password" required placeholder="Mot de passe" id="pw-login">
        <div class="eye" onclick="togglePw('pw-login', this)">
            <i class="fas fa-eye"></i>
        </div>
      </div>

      <div class="row-between">
        <label class="remember"><input type="checkbox"> Se souvenir de moi</label>
        <a href="#">Mot de passe oublié ?</a>
      </div>

      <button type="submit" class="btn-primary">
        Se connecter
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </button>

      <div class="divider">ou</div>

      <button type="button" class="btn-google">
        <svg width="18" height="18" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.6 6.1 29.6 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.7-.4-3.5Z"/><path fill="#FF3D00" d="m6.3 14.7 6.6 4.8C14.6 15.9 18.9 13 24 13c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.6 6.1 29.6 4 24 4 16.3 4 9.7 8.3 6.3 14.7Z"/><path fill="#4CAF50" d="M24 44c5.5 0 10.4-1.9 14.3-5.1l-6.6-5.4c-2 1.5-4.6 2.5-7.7 2.5-5.3 0-9.7-3.3-11.3-8H5.1V33C8.5 39.6 15.7 44 24 44Z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.3 4.2-4.2 5.5l6.6 5.4C41.4 35.6 44 30.2 44 24c0-1.3-.1-2.7-.4-3.5Z"/></svg>
        Continuer avec Google
      </button>

      <p class="switch-line">Pas encore de compte ? <a href="{{ url('inscription') }}">Créer un compte</a></p>
    </form>

    <!-- SIGNUP -->
    <form id="form-signup" class="active" method="POST" action="">
      <div class="card-head">
        <h2 class="title">Devenir Livreur</h2>
        <p class="subtitle">Rejoignez l'équipe logistique.</p>
      </div>

      <?php if(!empty($erreur)): ?>
          <div style="background:#FEF2F2; color:#B91C1C; padding:12px; border-radius:8px; font-size:13px; text-align:center; margin-bottom:10px; border:1px solid #FCA5A5;">
              <?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?>
          </div>
      <?php endif; ?>

      <div class="row2">
        <div class="field">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
          <input type="text" name="prenom" required placeholder="Prénom">
        </div>
        <div class="field">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
          <input type="text" name="nom" required placeholder="Nom">
        </div>
      </div>

      <div class="field">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>
        <input type="email" name="email" required placeholder="votre@email.com">
      </div>

      <div class="field">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3-8.7A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .3 2 .7 3a2 2 0 0 1-.4 2.1L8 10.1a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2-.5c1 .3 2 .5 3 .6a2 2 0 0 1 1.7 2Z"/></svg>
        <input type="tel" name="telephone" required placeholder="Ex: 0167424373">
      </div>

      <div class="field">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
        <input type="password" name="password" required placeholder="Mot de passe" id="pw-signup">
        <div class="eye" onclick="togglePw('pw-signup', this)">
            <i class="fas fa-eye"></i>
        </div>
      </div>

      <div class="field">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="11" width="16" height="9" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/><path d="m10.5 15.5 1.2 1.2 2.3-2.4"/></svg>
        <input type="password" name="password_confirmation" required placeholder="Confirmer le mot de passe" id="pw-signup-confirm">
        <div class="eye" onclick="togglePw('pw-signup-confirm', this)">
            <i class="fas fa-eye"></i>
        </div>
      </div>

      <p class="terms">En créant un compte, vous acceptez nos <a href="#">Conditions d'utilisation</a> et notre <a href="#">Politique de confidentialité</a>.</p>

      <button type="submit" class="btn-primary">
        S'inscrire
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </button>

      <div class="divider">ou</div>

      <button type="button" class="btn-google">
        <svg width="18" height="18" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.6 6.1 29.6 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.7-.4-3.5Z"/><path fill="#FF3D00" d="m6.3 14.7 6.6 4.8C14.6 15.9 18.9 13 24 13c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.6 6.1 29.6 4 24 4 16.3 4 9.7 8.3 6.3 14.7Z"/><path fill="#4CAF50" d="M24 44c5.5 0 10.4-1.9 14.3-5.1l-6.6-5.4c-2 1.5-4.6 2.5-7.7 2.5-5.3 0-9.7-3.3-11.3-8H5.1V33C8.5 39.6 15.7 44 24 44Z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.3 4.2-4.2 5.5l6.6 5.4C41.4 35.6 44 30.2 44 24c0-1.3-.1-2.7-.4-3.5Z"/></svg>
        S'inscrire avec Google
      </button>

      <p class="switch-line">Déjà un compte ? <a href="{{ url('connexion') }}">Se connecter</a></p>
    </form>

  </div>

  <a href="{{ url('/') }}" class="hint"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>

</div>

<script>
function togglePw(id, el){
  const input = document.getElementById(id);
  const icon = el.querySelector('i');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
}
</script>

</body>
</html>