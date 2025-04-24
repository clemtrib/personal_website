<template>
  <section id="contact" class="px-6 py-20 bg-[#112240] text-[#ccd6f6]">
    <h2 class="text-3xl font-bold mb-6 border-b-2 border-green-400 inline-block">Contact</h2>
    <form class="max-w-xl mx-auto grid gap-4">
      <input type="text" placeholder="Nom" class="p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]">
      <input type="email" placeholder="Email" class="p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]">
      <textarea rows="4" placeholder="Message" class="p-2 rounded bg-[#0a192f] text-white border border-[#64ffda]"></textarea>
      <button class="bg-green-400 text-[#0a192f] px-4 py-2 rounded hover:bg-green-300 transition">Envoyer</button>
    </form>
  </section>
</template>