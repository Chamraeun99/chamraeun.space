<template>
  <div class="join-page">

    <!-- ═══════════════════ HERO ═══════════════════ -->
    <section class="relative min-h-[45vh] sm:min-h-[55vh] flex items-center justify-center overflow-hidden">
      <div class="absolute inset-0 join-hero-grid opacity-[0.03] dark:opacity-[0.05]" />
      <div class="absolute top-1/4 -left-20 sm:-left-40 w-[300px] sm:w-[500px] h-[300px] sm:h-[500px] rounded-full bg-brand-violet/15 blur-[80px] sm:blur-[120px] animate-float" />
      <div class="absolute bottom-1/4 -right-20 sm:-right-40 w-[250px] sm:w-[400px] h-[250px] sm:h-[400px] rounded-full bg-brand-cyan/15 blur-[70px] sm:blur-[100px] animate-float" style="animation-delay: 3s" />
      <div class="absolute top-2/3 left-1/2 w-[200px] sm:w-[300px] h-[200px] sm:h-[300px] rounded-full bg-brand-violet/10 blur-[60px] sm:blur-[80px] animate-float" style="animation-delay: 5s" />

      <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center relative z-10">
        <div data-aos="fade-down" data-aos-duration="800" class="inline-flex items-center gap-2 px-4 py-1.5 mb-6 rounded-full border border-brand-violet/20 dark:border-brand-cyan/20 bg-white/60 dark:bg-dark-800/60 backdrop-blur-sm">
          <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span></span>
          <span class="text-xs font-code text-brand-violet dark:text-brand-cyan uppercase tracking-widest">We're Hiring</span>
        </div>
        <h1 data-aos="fade-up" data-aos-duration="1000" class="text-3xl sm:text-4xl md:text-5xl lg:text-7xl font-sans font-bold mb-4 sm:mb-6 leading-[1.1]">
          <span class="text-gray-900 dark:text-white">Build the</span><br />
          <span class="gradient-text">Future With Us</span>
        </h1>
        <p data-aos="fade-up" data-aos-delay="200" class="text-base sm:text-lg md:text-xl text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-sans leading-relaxed mb-6 sm:mb-8 px-2 sm:px-0">
          We're looking for passionate developers, designers, and creators who want to build meaningful software and grow together.
        </p>
        <div data-aos="fade-up" data-aos-delay="300">
          <a href="#apply" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-brand text-white font-semibold rounded-xl hover:shadow-glow transition-all duration-300 hover:-translate-y-1 text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /></svg>
            Apply Now
          </a>
        </div>
      </div>
    </section>

    <!-- ═══════════════════ WHY JOIN US ═══════════════════ -->
    <section class="pb-14 sm:pb-20 relative z-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10 sm:mb-14">
          <h2 data-aos="fade-up" class="text-2xl sm:text-3xl md:text-4xl font-sans font-bold text-gray-900 dark:text-white mb-4">
            Why <span class="gradient-text">Kalapak</span>?
          </h2>
          <p data-aos="fade-up" data-aos-delay="100" class="text-gray-500 dark:text-gray-400 max-w-xl mx-auto">
            More than just a team — we're a community of builders shaping Cambodia's tech future.
          </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div v-for="(perk, i) in perks" :key="i"
            data-aos="fade-up" :data-aos-delay="i * 80"
            class="group relative rounded-2xl border border-gray-200 dark:border-dark-600 bg-white/92 dark:bg-dark-800/60 backdrop-blur-sm p-7 hover:border-brand-violet/30 dark:hover:border-brand-cyan/30 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl dark:hover:shadow-glow/10">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 transition-transform duration-500 group-hover:scale-110"
              :class="perk.bgClass">
              <div v-html="perk.icon" />
            </div>
            <h3 class="text-base font-sans font-bold text-gray-900 dark:text-white mb-2">{{ perk.title }}</h3>
            <p class="text-sm text-gray-700 dark:text-gray-400 leading-relaxed">{{ perk.desc }}</p>
            <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-gradient-brand opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-b-2xl" />
          </div>
        </div>
      </div>
    </section>

    <!-- ═══════════════════ WHAT WE LOOK FOR & WHAT YOU GET ═══════════════════ -->
    <section class="pb-14 sm:pb-20 relative z-10">
      <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="grid md:grid-cols-2 gap-6 sm:gap-8">
          <!-- What We Look For -->
          <div data-aos="fade-right" class="rounded-2xl border border-gray-200 dark:border-dark-600 bg-white/92 dark:bg-dark-800/60 backdrop-blur-sm p-6 sm:p-8 md:p-10">
            <div class="flex items-center gap-3 mb-7">
              <div class="w-10 h-10 rounded-xl bg-brand-violet/10 dark:bg-brand-violet/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-brand-violet" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
              </div>
              <h3 class="text-xl font-sans font-bold text-gray-900 dark:text-white">What We Look For</h3>
            </div>
            <ul class="space-y-4">
              <li v-for="(item, i) in lookFor" :key="i" class="flex items-start gap-3.5">
                <div class="w-6 h-6 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900 dark:text-white">{{ item.title }}</p>
                  <p class="text-xs text-gray-700 dark:text-gray-400 mt-0.5">{{ item.desc }}</p>
                </div>
              </li>
            </ul>
          </div>

          <!-- What You'll Get -->
          <div data-aos="fade-left" class="rounded-2xl border border-gray-200 dark:border-dark-600 bg-white/92 dark:bg-dark-800/60 backdrop-blur-sm p-6 sm:p-8 md:p-10">
            <div class="flex items-center gap-3 mb-7">
              <div class="w-10 h-10 rounded-xl bg-brand-cyan/10 dark:bg-brand-cyan/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-brand-cyan" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
              </div>
              <h3 class="text-xl font-sans font-bold text-gray-900 dark:text-white">What You'll Get</h3>
            </div>
            <ul class="space-y-4">
              <li v-for="(item, i) in youGet" :key="i" class="flex items-start gap-3.5">
                <div class="w-6 h-6 rounded-lg bg-brand-cyan/20 dark:bg-brand-cyan/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-3.5 h-3.5 text-brand-cyan" fill="currentColor" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900 dark:text-white">{{ item.title }}</p>
                  <p class="text-xs text-gray-700 dark:text-gray-400 mt-0.5">{{ item.desc }}</p>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- ═══════════════════ OPEN ROLES ═══════════════════ -->
    <section class="pb-14 sm:pb-20 relative z-10">
      <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-8 sm:mb-12">
          <h2 data-aos="fade-up" class="text-2xl sm:text-3xl md:text-4xl font-sans font-bold text-gray-900 dark:text-white mb-4">
            Open <span class="gradient-text">Roles</span>
          </h2>
          <p data-aos="fade-up" data-aos-delay="100" class="text-gray-500 dark:text-gray-400 max-w-lg mx-auto">
            Find a role that matches your skillset and passion. All positions are remote-friendly.
          </p>
        </div>

        <div class="space-y-4">
          <div v-for="(role, i) in roles" :key="i"
            data-aos="fade-up" :data-aos-delay="i * 60"
            class="group rounded-2xl border border-gray-200 dark:border-dark-600 bg-white/92 dark:bg-dark-800/60 backdrop-blur-sm overflow-hidden hover:border-brand-violet/30 dark:hover:border-brand-cyan/30 transition-all duration-500 hover:shadow-lg dark:hover:shadow-glow/10">
            <div class="p-6 md:p-7 flex flex-col md:flex-row md:items-center gap-5">
              <!-- Icon -->
              <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                :class="role.bgClass">
                <div v-html="role.icon" />
              </div>
              <!-- Info -->
              <div class="flex-1 min-w-0">
                <h3 class="text-base font-sans font-bold text-gray-900 dark:text-white mb-1">{{ role.title }}</h3>
                <p class="text-sm text-gray-700 dark:text-gray-400">{{ role.desc }}</p>
              </div>
              <!-- Tags -->
              <div class="flex flex-wrap gap-2">
                <span v-for="tag in role.tags" :key="tag" class="px-2.5 py-1 rounded-full text-[10px] font-medium uppercase tracking-wider border border-gray-300 dark:border-dark-500 text-gray-600 dark:text-gray-400">
                  {{ tag }}
                </span>
              </div>
              <!-- Arrow -->
              <a href="#apply" class="flex-shrink-0 w-10 h-10 rounded-xl border border-gray-300 dark:border-dark-500 flex items-center justify-center text-gray-500 group-hover:border-brand-violet dark:group-hover:border-brand-cyan group-hover:text-brand-violet dark:group-hover:text-brand-cyan group-hover:bg-brand-violet/5 dark:group-hover:bg-brand-cyan/5 transition-all duration-300">
                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ═══════════════════ HOW IT WORKS ═══════════════════ -->
    <section class="pb-16 sm:pb-24 relative z-10">
      <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-10 sm:mb-14">
          <h2 data-aos="fade-up" class="text-2xl sm:text-3xl md:text-4xl font-sans font-bold text-gray-900 dark:text-white mb-4">
            How It <span class="gradient-text">Works</span>
          </h2>
          <p data-aos="fade-up" data-aos-delay="100" class="text-gray-500 dark:text-gray-400 max-w-lg mx-auto">
            Our application process is straightforward and transparent.
          </p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
          <div v-for="(step, i) in steps" :key="i"
            data-aos="fade-up" :data-aos-delay="i * 100"
            class="relative text-center">
            <!-- Connector line -->
            <div v-if="i < steps.length - 1" class="hidden lg:block absolute top-8 left-[60%] w-[80%] h-[2px] bg-gradient-to-r from-gray-200 dark:from-dark-500 to-transparent z-0" />
            <div class="relative z-10 w-16 h-16 rounded-2xl bg-gradient-brand flex items-center justify-center mx-auto mb-5 shadow-lg shadow-brand-violet/20">
              <span class="text-white font-sans font-bold text-xl">{{ i + 1 }}</span>
            </div>
            <h4 class="font-sans font-bold text-gray-900 dark:text-white mb-2 text-sm">{{ step.title }}</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ step.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ═══════════════════ APPLICATION FORM ═══════════════════ -->
    <section id="apply" class="pb-16 sm:pb-24 relative z-10">
      <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <div class="rounded-2xl sm:rounded-3xl border border-gray-200 dark:border-dark-600 bg-white/92 dark:bg-dark-800/60 backdrop-blur-sm overflow-hidden">
          <!-- Form header -->
          <div class="relative p-5 sm:p-8 md:p-10 pb-0">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-brand" />
            <div class="flex items-center gap-3 mb-2">
              <div class="w-10 h-10 rounded-xl bg-gradient-brand flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
              </div>
              <div>
                <h2 class="text-2xl font-sans font-bold text-gray-900 dark:text-white">Apply Now</h2>
                <p class="text-xs text-gray-700 dark:text-gray-400">Fields marked with * are required</p>
              </div>
            </div>
          </div>

          <!-- Success -->
          <div v-if="submitted" data-aos="zoom-in" class="p-8 md:p-10">
            <div class="text-center py-10">
              <div class="relative w-20 h-20 mx-auto mb-6">
                <div class="absolute inset-0 bg-green-400/20 rounded-full animate-ping" />
                <div class="relative w-20 h-20 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center shadow-lg shadow-green-500/30">
                  <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                </div>
              </div>
              <h3 class="text-2xl font-sans font-bold text-gray-900 dark:text-white mb-3">Application Submitted!</h3>
              <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-6 leading-relaxed">
                Thank you for your interest in joining Kalapak Code Team. We'll review your application and get back to you within a few days.
              </p>
              <button @click="resetForm" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium border border-gray-200 dark:border-dark-500 text-gray-600 dark:text-gray-400 hover:border-brand-violet dark:hover:border-brand-cyan hover:text-brand-violet dark:hover:text-brand-cyan transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.992 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" /></svg>
                Submit another application
              </button>
            </div>
          </div>

          <!-- Form -->
          <form v-else @submit.prevent="handleSubmit" class="p-8 md:p-10 space-y-6">
            <!-- Step indicator -->
            <div class="flex items-center gap-3 mb-2">
              <div v-for="s in 3" :key="s" class="flex items-center gap-3 flex-1">
                <div class="flex items-center gap-2">
                  <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300"
                    :class="currentStep >= s ? 'bg-gradient-brand text-white' : 'border border-gray-300 dark:border-dark-500 text-gray-400'">
                    {{ s }}
                  </div>
                  <span class="text-xs font-medium hidden sm:inline" :class="currentStep >= s ? 'text-brand-violet dark:text-brand-cyan' : 'text-gray-500 dark:text-gray-400'">
                    {{ ['Personal', 'Experience', 'Motivation'][s - 1] }}
                  </span>
                </div>
                <div v-if="s < 3" class="flex-1 h-[2px] rounded-full transition-all duration-300" :class="currentStep > s ? 'bg-gradient-brand' : 'bg-gray-200 dark:bg-dark-600'" />
              </div>
            </div>

            <!-- Step 1: Personal Info -->
            <div v-show="currentStep === 1" class="space-y-5">
              <div class="grid sm:grid-cols-2 gap-5">
                <div>
                  <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Full Name <span class="text-red-400">*</span></label>
                  <input v-model="form.name" type="text" required placeholder="e.g. Khat Vanna"
                    class="w-full px-4 py-3 rounded-xl bg-white dark:bg-dark-700 border border-gray-300 dark:border-dark-500 text-sm text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-violet/30 dark:focus:ring-brand-cyan/30 focus:border-brand-violet dark:focus:border-brand-cyan transition-all duration-300" />
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email <span class="text-red-400">*</span></label>
                  <input v-model="form.email" type="email" required placeholder="vanna@example.com"
                    class="w-full px-4 py-3 rounded-xl bg-white dark:bg-dark-700 border border-gray-300 dark:border-dark-500 text-sm text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-violet/30 dark:focus:ring-brand-cyan/30 focus:border-brand-violet dark:focus:border-brand-cyan transition-all duration-300" />
                </div>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Role Applied For <span class="text-red-400">*</span></label>
                <CustomSelect
                  v-model="form.role_applied"
                  :options="[
                    { label: 'Select a position', value: '' },
                    { label: 'Frontend Developer', value: 'Frontend Developer' },
                    { label: 'Backend Developer', value: 'Backend Developer' },
                    { label: 'Full-Stack Developer', value: 'Full-Stack Developer' },
                    { label: 'Mobile Developer', value: 'Mobile Developer' },
                    { label: 'UI/UX Designer', value: 'UI/UX Designer' },
                    { label: 'DevOps Engineer', value: 'DevOps Engineer' },
                    { label: 'Other', value: 'Other' }
                  ]"
                  placeholder="Select a position"
                />
              </div>
            </div>

            <!-- Step 2: Experience -->
            <div v-show="currentStep === 2" class="space-y-5">
              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Skills <span class="text-red-400">*</span></label>
                <input v-model="form.skills" type="text" required placeholder="e.g. Vue.js, Laravel, PostgreSQL, Docker"
                  class="w-full px-4 py-3 rounded-xl bg-white dark:bg-dark-700 border border-gray-300 dark:border-dark-500 text-sm text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-violet/30 dark:focus:ring-brand-cyan/30 focus:border-brand-violet dark:focus:border-brand-cyan transition-all duration-300" />
                <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1">Separate with commas</p>
              </div>
              <div class="grid sm:grid-cols-2 gap-5">
                <div>
                  <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">GitHub URL</label>
                  <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    <input v-model="form.github_url" type="url" placeholder="https://github.com/username"
                      class="w-full pl-10 pr-4 py-3 rounded-xl bg-white dark:bg-dark-700 border border-gray-300 dark:border-dark-500 text-sm text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-violet/30 dark:focus:ring-brand-cyan/30 focus:border-brand-violet dark:focus:border-brand-cyan transition-all duration-300" />
                  </div>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">LinkedIn URL</label>
                  <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    <input v-model="form.linkedin_url" type="url" placeholder="https://linkedin.com/in/username"
                      class="w-full pl-10 pr-4 py-3 rounded-xl bg-white dark:bg-dark-700 border border-gray-300 dark:border-dark-500 text-sm text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-violet/30 dark:focus:ring-brand-cyan/30 focus:border-brand-violet dark:focus:border-brand-cyan transition-all duration-300" />
                  </div>
                </div>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Portfolio URL</label>
                <div class="relative">
                  <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                  <input v-model="form.portfolio_url" type="url" placeholder="https://your-portfolio.com"
                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-white dark:bg-dark-700 border border-gray-300 dark:border-dark-500 text-sm text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-violet/30 dark:focus:ring-brand-cyan/30 focus:border-brand-violet dark:focus:border-brand-cyan transition-all duration-300" />
                </div>
              </div>
            </div>

            <!-- Step 3: Motivation -->
            <div v-show="currentStep === 3" class="space-y-5">
              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Why do you want to join Kalapak? <span class="text-red-400">*</span></label>
                <textarea v-model="form.motivation" required rows="6" placeholder="Tell us about yourself, your experience, and why you'd like to join our team..."
                  class="w-full px-4 py-3 rounded-xl bg-white dark:bg-dark-700 border border-gray-300 dark:border-dark-500 text-sm text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-violet/30 dark:focus:ring-brand-cyan/30 focus:border-brand-violet dark:focus:border-brand-cyan transition-all duration-300 resize-none" />
                <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 text-right">{{ form.motivation.length }} / 2000</p>
              </div>
            </div>

            <!-- Navigation buttons -->
            <div v-if="currentStep === 3" class="flex justify-center pt-2">
              <VueTurnstile :site-key="turnstileSiteKey" v-model="turnstileToken" />
            </div>
            <div class="flex items-center gap-4 pt-2">
              <button v-if="currentStep > 1" type="button" @click="currentStep--"
                class="px-5 py-3 rounded-xl text-sm font-medium border border-gray-200 dark:border-dark-500 text-gray-600 dark:text-gray-400 hover:border-brand-violet dark:hover:border-brand-cyan hover:text-brand-violet dark:hover:text-brand-cyan transition-all duration-300">
                Back
              </button>
              <div class="flex-1" />
              <button v-if="currentStep < 3" type="button" @click="nextStep"
                class="px-6 py-3 rounded-xl text-sm font-semibold bg-gradient-brand text-white hover:shadow-glow transition-all duration-300 hover:-translate-y-0.5">
                Continue
              </button>
              <button v-else type="submit" :disabled="submitting"
                class="inline-flex items-center gap-2 px-7 py-3 rounded-xl text-sm font-semibold bg-gradient-brand text-white hover:shadow-glow transition-all duration-300 hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-none">
                <svg v-if="!submitting" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                <div v-else class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                {{ submitting ? 'Submitting...' : 'Submit Application' }}
              </button>
            </div>

            <p v-if="error" class="text-sm text-red-500 text-center mt-2 px-4 py-2 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
              {{ error }}
            </p>
          </form>
        </div>
      </div>
    </section>

    <!-- ═══════════════════ FAQ ═══════════════════ -->
    <section class="pb-24 relative z-10">
      <div class="max-w-3xl mx-auto px-4">
        <div class="text-center mb-12">
          <h2 data-aos="fade-up" class="text-3xl md:text-4xl font-sans font-bold text-gray-900 dark:text-white mb-4">
            Frequently <span class="gradient-text">Asked</span>
          </h2>
        </div>
        <div class="space-y-3">
          <div v-for="(faq, i) in faqs" :key="i"
            data-aos="fade-up" :data-aos-delay="i * 60"
            class="rounded-2xl border border-gray-200 dark:border-dark-600 bg-white/92 dark:bg-dark-800/60 backdrop-blur-sm overflow-hidden">
            <button @click="toggleFaq(i)" class="w-full flex items-center justify-between p-5 md:p-6 text-left">
              <h4 class="text-sm font-sans font-bold text-gray-900 dark:text-white pr-4">{{ faq.q }}</h4>
              <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 flex-shrink-0 transition-transform duration-300" :class="{ 'rotate-180': openFaq === i }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
            </button>
            <div v-show="openFaq === i" class="px-5 md:px-6 pb-5 md:pb-6 -mt-1">
              <p class="text-sm text-gray-700 dark:text-gray-400 leading-relaxed">{{ faq.a }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { memberApi } from '@/services/api'
import CustomSelect from '@/components/common/CustomSelect.vue'
import VueTurnstile from 'vue-turnstile'

const currentStep = ref(1)
const submitting = ref(false)
const submitted = ref(false)
const error = ref('')
const openFaq = ref(null)
const turnstileSiteKey = useTurnstileSiteKey()
const turnstileToken = ref('')

const form = reactive({
  name: '',
  email: '',
  role_applied: '',
  skills: '',
  github_url: '',
  linkedin_url: '',
  portfolio_url: '',
  motivation: '',
})

const perks = [
  { title: 'Real Projects', desc: 'Work on production apps used by real people — not toy demos.', bgClass: 'bg-violet-100 dark:bg-violet-900/20', icon: '<svg class="w-6 h-6 text-brand-violet" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg>' },
  { title: 'Mentorship', desc: 'Learn from experienced developers through code review and pairing sessions.', bgClass: 'bg-cyan-100 dark:bg-cyan-900/20', icon: '<svg class="w-6 h-6 text-brand-cyan" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>' },
  { title: 'Community', desc: 'Join a supportive network of developers building Cambodia\'s tech future.', bgClass: 'bg-violet-100 dark:bg-violet-900/20', icon: '<svg class="w-6 h-6 text-brand-violet" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>' },
  { title: 'Growth', desc: 'Build your portfolio, sharpen your skills, and accelerate your career.', bgClass: 'bg-cyan-100 dark:bg-cyan-900/20', icon: '<svg class="w-6 h-6 text-brand-cyan" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg>' },
]

const lookFor = [
  { title: 'Passion for coding and technology', desc: 'You love building things with code and are always looking for new challenges.' },
  { title: 'Willingness to learn and grow', desc: 'You don\'t need to know everything — just be eager to learn.' },
  { title: 'Team collaboration mindset', desc: 'You communicate well, give constructive feedback, and support your teammates.' },
  { title: 'At least one programming language', desc: 'Familiarity with any language or framework — JavaScript, Python, PHP, Java, etc.' },
  { title: 'Problem-solving attitude', desc: 'You enjoy debugging, tackling tricky logic, and finding creative solutions.' },
]

const youGet = [
  { title: 'Real project experience', desc: 'Work on production-grade applications from start to deployment.' },
  { title: 'Mentorship from seniors', desc: 'Get code reviews, guidance, and pair-programming sessions with experienced devs.' },
  { title: 'Portfolio-worthy projects', desc: 'Build projects you can proudly show to future employers or clients.' },
  { title: 'Tech community networking', desc: 'Connect with developers, attend meetups, and grow your professional network.' },
  { title: 'Flexible & remote-friendly', desc: 'Work from anywhere — we value output over hours at a desk.' },
]

const roles = [
  { title: 'Frontend Developer', desc: 'Build beautiful, responsive interfaces with Vue.js and modern CSS.', tags: ['Vue.js', 'Tailwind', 'TypeScript'], bgClass: 'bg-violet-50 dark:bg-violet-900/20', icon: '<svg class="w-6 h-6 text-brand-violet" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/></svg>' },
  { title: 'Backend Developer', desc: 'Design APIs, databases, and server-side logic with Laravel & PostgreSQL.', tags: ['Laravel', 'PostgreSQL', 'REST'], bgClass: 'bg-cyan-50 dark:bg-cyan-900/20', icon: '<svg class="w-6 h-6 text-brand-cyan" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008z"/></svg>' },
  { title: 'Full-Stack Developer', desc: 'Own features end-to-end, from database schema to pixel-perfect UI.', tags: ['Vue.js', 'Laravel', 'Docker'], bgClass: 'bg-violet-50 dark:bg-violet-900/20', icon: '<svg class="w-6 h-6 text-brand-violet" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L12 12.75 6.429 9.75m11.142 0l4.179 2.25-9.75 5.25-9.75-5.25 4.179-2.25"/></svg>' },
  { title: 'Mobile Developer', desc: 'Create cross-platform mobile apps with React Native or Flutter.', tags: ['React Native', 'Flutter', 'Mobile'], bgClass: 'bg-cyan-50 dark:bg-cyan-900/20', icon: '<svg class="w-6 h-6 text-brand-cyan" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>' },
  { title: 'UI/UX Designer', desc: 'Design intuitive, user-centered interfaces and delightful experiences.', tags: ['Figma', 'Prototyping', 'Research'], bgClass: 'bg-violet-50 dark:bg-violet-900/20', icon: '<svg class="w-6 h-6 text-brand-violet" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/></svg>' },
]

const steps = [
  { title: 'Apply', desc: 'Fill out the form with your info, skills, and motivation.' },
  { title: 'Review', desc: 'Our team reviews your application within a few days.' },
  { title: 'Interview', desc: 'A casual chat to learn about you and answer your questions.' },
  { title: 'Welcome!', desc: 'You\'re in! Start collaborating on real projects right away.' },
]

const faqs = [
  { q: 'Do I need professional experience?', a: 'Not at all! We welcome students, self-taught developers, and anyone passionate about coding. What matters most is your willingness to learn and grow with the team.' },
  { q: 'Is this a paid position?', a: 'Kalapak is a community-driven team focused on learning and building together. Currently, positions are volunteer-based, but team members gain real experience, mentorship, and portfolio projects.' },
  { q: 'What is the time commitment?', a: 'We\'re flexible. Most members contribute 5-10 hours per week, but you can adjust based on your schedule. We value consistency over quantity.' },
  { q: 'What tech stack do you use?', a: 'Our primary stack is Vue.js + Tailwind CSS on the frontend and Laravel + PostgreSQL on the backend, all containerized with Docker. But we\'re always exploring new technologies.' },
  { q: 'Where is the team based?', a: 'We\'re based in Phnom Penh, Cambodia, but work entirely remote. You can contribute from anywhere as long as you communicate and collaborate effectively.' },
  { q: 'How long does the review process take?', a: 'We typically review applications within 3-5 business days. You\'ll receive an email with our decision and next steps.' },
]

function nextStep() {
  if (currentStep.value === 1 && (!form.name || !form.email || !form.role_applied)) {
    error.value = 'Please fill in all required fields before continuing.'
    return
  }
  if (currentStep.value === 2 && !form.skills) {
    error.value = 'Please list your skills before continuing.'
    return
  }
  error.value = ''
  currentStep.value++
}

function toggleFaq(i) {
  openFaq.value = openFaq.value === i ? null : i
}

function resetForm() {
  submitted.value = false
  currentStep.value = 1
  Object.assign(form, { name: '', email: '', role_applied: '', skills: '', github_url: '', linkedin_url: '', portfolio_url: '', motivation: '' })
}

async function handleSubmit() {
  submitting.value = true
  error.value = ''
  try {
    await memberApi.submitApplication({ ...form, turnstile_token: turnstileToken.value })
    submitted.value = true
  } catch (e) {
    error.value = e.response?.data?.message || 'Failed to submit. Please try again.'
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.join-hero-grid {
  background-image:
    linear-gradient(rgba(123, 47, 255, 0.1) 1px, transparent 1px),
    linear-gradient(90deg, rgba(123, 47, 255, 0.1) 1px, transparent 1px);
  background-size: 60px 60px;
}
</style>
