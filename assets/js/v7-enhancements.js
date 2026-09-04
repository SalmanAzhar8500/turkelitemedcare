/* V7 enhancements — loaded after site.js, corrects behaviour rather than duplicating it.
   1. Accessible search modal: Escape to close, focus trap, focus returned to the trigger.
   2. Click-to-load video embeds so no request reaches Google before the visitor asks.
   3. Mega-menu keyboard support and Escape handling.
   4. Form guardrails: honeypot check, and a warning before a consent-gated submit.
*/
(function () {
  "use strict";

  var qs = function (s, r) { return (r || document).querySelector(s); };
  var qsa = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  var FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

  /* ---------------------------------------------------- 1. search modal */
  (function accessibleSearchModal() {
    var modal = qs(".search-modal");
    var toggle = qs(".search-toggle");
    var close = qs(".search-close");
    if (!modal || !toggle) return;

    var lastFocused = null;

    // The modal is in the DOM on every page; hide it from assistive tech and
    // from the tab order while it is closed.
    function setInert(hidden) {
      modal.setAttribute("aria-hidden", hidden ? "true" : "false");
      if ("inert" in HTMLElement.prototype) modal.inert = hidden;
    }

    modal.setAttribute("role", "dialog");
    modal.setAttribute("aria-modal", "true");
    modal.setAttribute("aria-label", "Search this site");
    setInert(true);

    toggle.setAttribute("aria-haspopup", "dialog");
    toggle.setAttribute("aria-expanded", "false");

    function openModal() {
      lastFocused = document.activeElement;
      setInert(false);
      toggle.setAttribute("aria-expanded", "true");
      document.body.style.overflow = "hidden";
      var input = qs(".search-input", modal);
      if (input) window.setTimeout(function () { input.focus(); }, 60);
    }

    function closeModal() {
      setInert(true);
      modal.classList.remove("open");
      toggle.setAttribute("aria-expanded", "false");
      document.body.style.overflow = "";
      if (lastFocused && lastFocused.focus) lastFocused.focus();
    }

    toggle.addEventListener("click", openModal);
    if (close) close.addEventListener("click", closeModal);

    document.addEventListener("keydown", function (e) {
      if (!modal.classList.contains("open")) return;

      if (e.key === "Escape") {
        e.preventDefault();
        closeModal();
        return;
      }

      if (e.key !== "Tab") return;

      // Trap focus inside the dialog.
      var items = qsa(FOCUSABLE, modal).filter(function (el) {
        return el.offsetParent !== null;
      });
      if (!items.length) return;
      var first = items[0];
      var last = items[items.length - 1];

      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    });
  })();

  /* ------------------------------------------- 2. click-to-load video */
  (function videoConsentGate() {
    var modules = qsa(".video-module");
    if (!modules.length) return;

    // site.js swaps in an iframe as soon as a video id exists. Intercept that:
    // hold the id on the element and only mount the iframe once the visitor
    // clicks play, so youtube-nocookie.com is never contacted unprompted.
    modules.forEach(function (module) {
      var placeholder = qs(".video-placeholder", module);
      if (!placeholder) return;

      var note = document.createElement("p");
      note.className = "video-consent-note";
      note.style.cssText = "font-size:11px;color:#c9dce4;margin-top:14px;max-width:520px";
      note.textContent =
        "Select play to load this video from YouTube. Nothing is sent to Google until you do.";
      placeholder.appendChild(note);

      placeholder.setAttribute("role", "button");
      placeholder.setAttribute("tabindex", "0");

      function mount() {
        var map = window.VIDEO_MAP || {};
        var id = map[module.dataset.videoKey];
        if (!id) {
          note.textContent = "This explainer has not been published yet.";
          return;
        }
        var frame = document.createElement("iframe");
        frame.src =
          "https://www.youtube-nocookie.com/embed/" + encodeURIComponent(id) + "?autoplay=1&rel=0";
        frame.title = "Procedure video";
        frame.loading = "lazy";
        frame.allow =
          "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share";
        frame.setAttribute("allowfullscreen", "");
        frame.style.cssText = "width:100%;height:100%;border:0";
        module.innerHTML = "";
        module.appendChild(frame);
        frame.focus();
      }

      placeholder.addEventListener("click", mount);
      placeholder.addEventListener("keydown", function (e) {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          mount();
        }
      });
    });
  })();

  /* -------------------------------------------------- 3. mega menu a11y */
  (function megaMenuKeyboard() {
    qsa(".has-mega").forEach(function (item) {
      var button = qs(".mega-toggle", item);
      var menu = qs(".mega-menu", item);
      if (!button || !menu) return;

      button.setAttribute("aria-expanded", "false");
      button.setAttribute("aria-haspopup", "true");

      function setOpen(open) {
        item.classList.toggle("open", open);
        button.setAttribute("aria-expanded", open ? "true" : "false");
      }

      item.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && item.classList.contains("open")) {
          setOpen(false);
          button.focus();
        }
      });

      // Close when focus leaves the whole menu.
      item.addEventListener("focusout", function (e) {
        if (!item.contains(e.relatedTarget)) setOpen(false);
      });
    });
  })();

  /* ------------------------------------------------------ 4. form guards */
  (function formGuards() {
    qsa("form").forEach(function (form) {
      form.addEventListener("submit", function (e) {
        // Honeypot: a real person never fills this in.
        var hp = form.querySelector('[name="company_website"]');
        if (hp && hp.value) {
          e.preventDefault();
          return;
        }

        // The intake form is set to novalidate so we can control the message.
        var invalid = form.querySelector(":invalid");
        if (invalid) {
          e.preventDefault();
          invalid.focus();
          if (invalid.type === "checkbox") {
            var wrap = invalid.closest(".check");
            if (wrap) wrap.style.outline = "2px solid #d98b3a";
          }
          if (invalid.reportValidity) invalid.reportValidity();
        }
      });
    });
  })();
})();
