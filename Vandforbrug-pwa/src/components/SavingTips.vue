<template>
    <div>
        <h1>
            Hvordan sparer jeg på vandet?
        </h1>
        <p>
          Vi bruger meget vand i dagligdagen - når vi går i bad, børster tænder og går på toilettet eller når vi vasker tøj, vasker bil og vasker op. Se her, hvordan du nemt kan spare penge og samtidig skåne miljøet.
        </p>
        <ul class="list-group">
            <li class="list-group-item" style="--animation-order: 1;">Undgå at skifte vand alt for tit i jeres badebassin. Det koster nemlig omkring 700 kroner at fylde et badebassin på 10.000 liter.</li>
            <li class="list-group-item" style="--animation-order: 2;">Pas på toiletter der løber. Det kan koste flere tusinde kroner om året.</li>
            <li class="list-group-item" style="--animation-order: 3;">Tag brusebad i stedet for karbad.</li>
            <li class="list-group-item" style="--animation-order: 4;">Tag korte brusebade og luk for bruseren, når du sæber dig ind.</li>
            <li class="list-group-item" style="--animation-order: 5;">Luk for vandet, når du børster tænder.</li>
            <li class="list-group-item" style="--animation-order: 6;">Vand kun haven, hvis det er helt nødvendigt. Brug vandkande i stedet for slange og saml regnvand.</li>
            <li class="list-group-item" style="--animation-order: 7;" >Tænd kun opvaskemaskinen og vaskemaskinen, når den er helt fyldt.</li>
            <li class="list-group-item" style="--animation-order: 8;">Brug en balje, hvis du vasker op i hånden.</li>
            <li class="list-group-item" style="--animation-order: 9;">Køl dit vand i køleskabet i stedet for at lade vandhanen løbe, indtil vandet bliver koldt.</li>
            <li class="list-group-item" style="--animation-order: 10;">Køb vaskemaskiner og opvaskemaskiner med lavt vandforbrug.</li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'SavingTips',
}
</script>

<style scoped>
    /* ul{
  margin:120;
  padding:50px;
} 
ul li    {
  padding:0;
  width:500px;
  margin:10px 0;
} */
h1 {
  margin: 20px auto;
  padding: 10px;
}
p {
   margin: 10px;
 }

ul {
  padding: 0;
  display: block;
  margin: 24px auto;
  width: 320px;
}

li {
  display: block;
  height: 80px;
  width: 400px;
  background-color: #2fa6dd57;
  animation-name: animateIn;
  animation-duration: 350ms;
  animation-delay: calc(var(--animation-order) * 100ms);
  animation-fill-mode: both;
  animation-timing-function: ease-in-out;
}

li + li {
  margin-top: 3px;
}

@keyframes animateIn {
  0% {
    opacity: 0;
    transform: scale(0.6) translateY(-8px);
  }
  
  100% {
    opacity: 1;
  }
}
</style>
