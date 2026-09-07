$(function () {
  gsap.registerPlugin(ScrollTrigger);
  gsap.registerPlugin(ScrollToPlugin);

  var init = {
    splitText: function (selector, types = ["chars"], tagName = "span") {
      if (typeof SplitType === "undefined") {
        console.error("SplitType.js is not loaded");
        return;
      }

      return new SplitType(selector, {
        types,
        tagName
      });
    },

    header: function () {

      document.querySelectorAll("header span[data-key]").forEach(link => {
        const href = link.getAttribute("href");
        if (!href) return;

        const linkURL = new URL(href, window.location.origin);

        const normalize = (path) =>
          path
            .replace(/\/{2,}/g, "/")          // collapse //
            .replace(/\/index\.html$/, "")    // remove index.html
            .replace(/^\/(fr)(?=\/|$)/, "")   // remove language prefix
            .replace(/\.html$/, "")           // remove .html
            .replace(/\/$/, "");              // remove trailing slash

        const linkPath = normalize(linkURL.pathname);
        const currentPath = normalize(window.location.pathname);

        const isHome =
          (linkPath === "" || linkPath === "/") &&
          (currentPath === "" || currentPath === "/");

        if (isHome || linkPath === currentPath) {
          link.classList.add("active");
        }
      });


      // const menuTrigger = document.querySelector('.menu-wrapper');

      // menuTrigger.addEventListener('click', function () {
      //   document.querySelector('.hamburger-menu').classList.toggle('animate');
      // });

      const menuTrigger = document.querySelector('.menu-wrapper');
      const menu = document.querySelector('.mobile-menu-wrapper');
      const hamburger = document.querySelector('.hamburger-menu');

      const menuTL = gsap.timeline({
        paused: true,
        defaults: { ease: "power3.inOut" }
      });

      // Menu reveal
      menuTL
        .set(menu, { pointerEvents: "auto" })
        .to(menu, {
          clipPath: "polygon(0 0, 100% 0, 100% 100%, 0 100%)",
          duration: 0.6
        })
        // Menu items stagger
        .from(
          ".mobile-menu-wrapper li",
          {
            y: 30,
            opacity: 0,
            stagger: 0.08,
            duration: 0.4
          },
          "-=0.3"
        )
        .from(
          ".mobile-menu-wrapper .btn",
          {
            y: 20,
            opacity: 0,
            duration: 0.3
          },
          "-=0.25"
        );

      let isOpen = false;

      menuTrigger.addEventListener("click", () => {
        hamburger.classList.toggle("animate");

        if (!isOpen) {
          menuTL.play();
          document.body.style.overflow = "hidden";
        } else {
          menuTL.reverse();
          document.body.style.overflow = "";
        }

        isOpen = !isOpen;
      });
    },

    homeLoad: function () {

      if (!document.querySelector('.hero')) return;

      gsap.set('.site-wrapper', { autoAlpha: 1 });
      gsap.set('.main-header', { yPercent: -100, opacity: 0 });

      const tl = gsap.timeline({
        defaults: {
          ease: "power3.out",
          duration: 0.7
        }
      });

      const heroTitleSplit = init.splitText(".hero h1 .split-line", ["words"]);

      tl
        .fromTo(
          ".main-header", {
          yPercent: -100,
          opacity: 0,
        }, {
          yPercent: 0,
          opacity: 1,
          duration: 1,
        }).from(".hero", {
          opacity: 0,
          duration: 0.8,
          scale: 1.05,
          ease: "power3.out"
        }, "-=0.4").fromTo(
          ".hero-circles-wrapper .circle", {
          scale: 0,
          opacity: 0
        }, {
          scale: 1,
          opacity: 1,
          stagger: 0.15,
          duration: 0.6,
          ease: "back.out(1.7)"
        }, "-=0.4").fromTo(
          ".hero-icon-holder", {
          scale: 0.5,
          opacity: 0
        }, {
          scale: 1,
          opacity: 1,
          duration: 0.8,
          ease: "back.out(1.7)"
        }, "-=0.2").fromTo(
          '.hero-title .heading', {
          y: 40,
          opacity: 0,
          scale: 1.1,
          filter: "blur(10px)"
        }, {
          y: 0,
          opacity: 1,
          scale: 1,
          filter: "blur(0px)",
          duration: 0.9,
          ease: "power3.out",
          force3D: true
        }, "-=0.4").fromTo(
          '.hero-title p', {
          y: 40,
          opacity: 0,
        }, {
          y: 0,
          opacity: 1,
          duration: 0.9,
          ease: "power3.out",
          force3D: true
        }, "-=0.4"
        ).from('.hero-btns', {
          y: 40,
          opacity: 0,
          ease: "power3.out",
        }, "-=0.4");
    },

    pageLoad: function () {
      const pageHeader = document.querySelector('.page-header');
      if (!pageHeader) return;

      const tl = gsap.timeline({
        defaults: {
          ease: "power3.out",
          duration: 0.7
        }
      });

      // Initial state (safe for Barba)
      gsap.set('.site-wrapper', { autoAlpha: 1 });
      gsap.set('.main-header', { yPercent: -100, opacity: 0 });

      tl
        // Header enters
        .to('.main-header', {
          yPercent: 0,
          opacity: 1,
          duration: 1
        })

        // Page header container
        .from('.page-header', {
          opacity: 0,
          scale: 1.15,
          ease: "back.out(1.7)"
        }, '-=0.8')

        // Circles
        .fromTo(
          '.hero-circles-wrapper .circle',
          { scale: 0, opacity: 0 },
          {
            scale: 1,
            opacity: 1,
            stagger: 0.15,
            duration: 0.6,
            ease: "back.out(1.7)"
          },
          '-=0.5'
        )

        // Breadcrumbs
        .from('.breadcrumbs', {
          opacity: 0,
          y: 40,
          filter: 'blur(10px)',
          duration: 0.5
        }, '-=0.6')

        // Title
        .from('.page-header h1', {
          opacity: 0,
          y: 40,
          filter: 'blur(10px)',
          ease: "back.out(1.7)"
        }, '-=0.4')

        // Lead
        

      if ($('.page-header .lead').length){
        tl.from('.page-header .lead', {
            opacity: 0,
            y: 30,
            filter: 'blur(10px)',
            ease: "back.out(1.7)"
          }, '-=0.3');
        }
    },

    heroMouseParallax: function () {

      const hero = document.querySelector(".hero");
      if (!hero) return;

      // ICON (closest layer)
      const iconX = gsap.quickTo(".hero-icon-holder", "x", {
        duration: 0.9,
        ease: "power3.out"
      });
      const iconY = gsap.quickTo(".hero-icon-holder", "y", {
        duration: 0.9,
        ease: "power3.out"
      });

      // CIRCLES (depth layers)
      const c1X = gsap.quickTo(".circle.one", "x", { duration: 1.1, ease: "power3.out" });
      const c1Y = gsap.quickTo(".circle.one", "y", { duration: 1.1, ease: "power3.out" });

      const c2X = gsap.quickTo(".circle.two", "x", { duration: 1.3, ease: "power3.out" });
      const c2Y = gsap.quickTo(".circle.two", "y", { duration: 1.3, ease: "power3.out" });

      const c3X = gsap.quickTo(".circle.three", "x", { duration: 1.6, ease: "power3.out" });
      const c3Y = gsap.quickTo(".circle.three", "y", { duration: 1.6, ease: "power3.out" });

      hero.addEventListener("mousemove", (e) => {
        const rect = hero.getBoundingClientRect();

        // NORMALIZED (-0.5 → 0.5)
        const x = (e.clientX - rect.left) / rect.width - 0.5;
        const y = (e.clientY - rect.top) / rect.height - 0.5;

        // ICON
        iconX(x * 100);
        iconY(y * 100);

        // CIRCLES (depth illusion)
        c1X(x * 40);
        c1Y(y * 40);

        c2X(x * 60);
        c2Y(y * 60);

        c3X(x * 80);
        c3Y(y * 80);
      });

      hero.addEventListener("mouseleave", () => {
        gsap.to(
          [
            ".hero-icon-holder",
            ".circle.one",
            ".circle.two",
            ".circle.three"
          ],
          {
            x: 0,
            y: 0,
            duration: 1.4,
            ease: "power3.out"
          }
        );
      });
    },

    homeScrolAnimation: function () {

      const featCard = document.querySelector('.features-home .feat-card');

      if (featCard) {
        gsap.utils.toArray(".features-home .feat-card").forEach((card, i) => {
          gsap.fromTo(card, {
            opacity: 0,
            scale: 1.4,
            xPercent: 40,
          }, {
            xPercent: 0,
            scale: 1,
            opacity: 1,
            duration: 0.8,
            ease: "power3.out",
            scrollTrigger: {
              trigger: card,
              start: "top 80%",
              toggleActions: "play none none reverse",
            }
          });
        });
      }
    },

    scrollAnimation: function () {

      const items = document.querySelectorAll("[data-anim]");
      if (items.length) {
        items.forEach(el => {
          const anim = el.dataset.anim;
          const delay = parseFloat(el.dataset.delay) || 0;
          const stagger = parseFloat(el.dataset.stagger) || 0;
          const split = el.dataset.split;

          // NEW
          const offset = el.dataset.offset || "85%";
          const once = el.hasAttribute("data-once");

          // --------------------------------
          // 1. Resolve animation targets
          // --------------------------------
          let targets = [el];

          if (el.hasAttribute("data-children")) {
            targets = Array.from(el.children);
          }

          // --------------------------------
          // 2. Split text (gradient-safe)
          // --------------------------------
          if (split) {
            if (el.splitType) el.splitType.revert();

            el.splitType = init.splitText(el, split.split(","));

            if (el.querySelector(".text-white-gradient, .text-gradient-primary")) {
              targets = el.querySelectorAll(".word, .line");
            } else {
              if (split.includes("chars")) targets = el.querySelectorAll(".char");
              else if (split.includes("words")) targets = el.querySelectorAll(".word");
              else targets = el.querySelectorAll(".line");
            }
          }

          // --------------------------------
          // Accessibility: reduced motion
          // --------------------------------
          if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
            gsap.set(targets, { opacity: 1, y: 0, rotationX: 0 });
            return;
          }

          // --------------------------------
          // ScrollTrigger base config
          // --------------------------------
          const scrollConfig = {
            trigger: el,
            start: `top ${offset}`,
            toggleActions: once
              ? "play none none none"
              : "play none none reverse",
            once
          };

          // --------------------------------
          // 3. fade-up
          // --------------------------------
          if (anim === "fade-up") {
            gsap.fromTo(
              targets,
              {
                y: 40,
                opacity: 0,
                filter: "blur(10px)"
              },
              {
                y: 0,
                opacity: 1,
                filter: "blur(0px)",
                duration: 0.9,
                delay,
                stagger,
                ease: "power3.out",
                scrollTrigger: scrollConfig
              }
            );
          }

          // --------------------------------
          // 4. flip-up (3D safe)
          // --------------------------------
          if (anim === "flip-up") {
            gsap.fromTo(
              targets,
              {
                y: 40,
                rotationX: -80,
                opacity: 0,
                filter: "blur(10px)",
                transformOrigin: "center bottom",
                transformPerspective: 1000
              },
              {
                y: 0,
                rotationX: 0,
                opacity: 1,
                filter: "blur(0px)",
                duration: 1,
                delay,
                stagger,
                ease: "power3.out",
                scrollTrigger: scrollConfig
              }
            );
          }
        });
      }

      const spinBounce = document.querySelector(".spin-bounce");

      if (spinBounce) {
        gsap.set(spinBounce, {
          transformStyle: "preserve-3d",
          transformPerspective: 800,
          willChange: "transform"
        });

        gsap.to(spinBounce, {
          rotationY: 360,
          duration: 2,
          ease: "power2.inOut",
          repeat: -1,
          repeatDelay: 1
        });
      }

      const prodCards = document.querySelectorAll(".products .products-card");

      if (prodCards.length) {

        prodCards.forEach((card, i) => {
          const blurImage = card.querySelector(".products-image .blur-image");
          if (!blurImage) return;

          const isOdd = i % 2 === 0;

          const strength = 20; // movement intensity (px)

          card.addEventListener("mouseenter", () => {
            gsap.to(blurImage, {
              opacity: 1,
              duration: 0.3,
              ease: "power2.out"
            });
          });

          card.addEventListener("mousemove", (e) => {
            const rect = card.getBoundingClientRect();

            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            gsap.to(blurImage, {
              // x: (x / rect.width) * strength,
              // y: (y / rect.height) * strength,
              x: isOdd ? -x / 3 : x / 3,
              y: y / 3,
              duration: 0.4,
              ease: "power3.out"
            });
          });

          card.addEventListener("mouseleave", () => {
            gsap.to(blurImage, {
              x: 0,
              y: 0,
              // opacity: 0,
              duration: 0.6,
              ease: "power3.out"
            });
          });
        });

        prodCards.forEach((card, i) => {
          const prodImage = card.querySelector(".products-image img");
          const prodContent = card.querySelector(".products-content");
          const blurImage = card.querySelector(".products-image .blur-image")

          const isOdd = i % 2 === 0;

          gsap.timeline({
            scrollTrigger: {
              trigger: card,
              start: "top 85%",
              toggleActions: "play none none reverse",
              // markers: true
            }
          })
            // Card reveal
            .from(card, {
              opacity: 0,
              y: 40,
              duration: 0.6,
              ease: "power3.out"
            })

            // .from(blurImage, {
            //   opacity: 0,
            //   scale: 1.2,
            //   duration: 0.2,
            //   ease: "power3.out"
            // }, ">")

            .fromTo(prodImage, {
              y: 40,
              rotationX: -80,
              opacity: 0,
              filter: "blur(10px)",
              transformOrigin: "center bottom",
              transformPerspective: 1000
            }, {
              y: 0,
              rotationX: 0,
              opacity: 1,
              filter: "blur(0px)",
              duration: 1,
              ease: "power3.out",
            }, "-=0.8")

            .from(prodContent, {
              opacity: 0,
              y: 100,
              filter: "blur(20px)",
              duration: 0.4,
              ease: "power3.out"
            }, "-=0.6");
        });

      };

      const tutorials = document.querySelector('.tutorials');

      if (tutorials) {
        gsap.utils.toArray(".tutorial").forEach((tutorial, i) => {
          gsap.fromTo(tutorial, {
            y: 40,
            rotationY: -30,
            opacity: 0,
            scale: 0.7,
            filter: "blur(10px)",
            transformOrigin: "center bottom",
            transformPerspective: 1000
          }, {
            y: 0,
            rotationY: 0,
            opacity: 1,
            filter: "blur(0px)",
            scale: 1,
            duration: 1,
            ease: "power3.out",
            scrollTrigger: {
              trigger: tutorial,
              start: "top 80%",
              toggleActions: "play none none reverse",
            }
          }
          );
        });
      };
    },

    ctoaAnimate: function () {
      if (!document.querySelector('.ctoa')) return;

      const ctoaTl = gsap.timeline({
        scrollTrigger: {
          trigger: ".ctoa",
          start: "top 75%",
          toggleActions: "play none none reverse"
        },
        defaults: {
          ease: "power3.out"
        }
      });

      ctoaTl.fromTo(".ctoa-icon-holder", {
        scale: 0.6,
        opacity: 0
      }, {
        scale: 1,
        opacity: 1,
        duration: 0.7,
        ease: "back.out(1.7)"
      }).fromTo(".ctoa-heading", {
        y: 40,
        opacity: 0,
        scale: 1.05,
        filter: "blur(12px)"
      }, {
        y: 0,
        opacity: 1,
        scale: 1,
        filter: "blur(0px)",
        duration: 0.9
      }, "-=0.2").fromTo(".ctoa p", {
        y: 30,
        opacity: 0
      }, {
        y: 0,
        opacity: 1,
        duration: 0.7
      }, "-=0.4").fromTo(".ctoa .btn", {
        y: 20,
        opacity: 0
      }, {
        y: 0,
        opacity: 1,
        duration: 0.6
      }, "-=0.3").fromTo(".ctoa-circles-wrapper .circle", {
        scale: 0,
        opacity: 0
      }, {
        scale: 1,
        opacity: 1,
        stagger: 0.15,
        duration: 0.6,
        ease: "back.out(1.7)"
      }, "-=0.8");
    },

    coreFeatures: function () {

      const section = document.querySelector(".core-features");

      if (!document.querySelector('.core-features')) return;

      const track = section.querySelector(".scrolling-wrapper");
      const items = gsap.utils.toArray(".scroll-item", section);

      const getScrollEnd = () =>
        (track.scrollWidth - window.innerWidth) + 100;

      // Horizontal scroll
      const horizontalTween = gsap.to(track, {
        x: () => -getScrollEnd(),
        ease: "none",
        scrollTrigger: {
          trigger: section,
          start: "top-=120 top",
          end: () => `+=${getScrollEnd()}`,
          scrub: true,
          pin: true,
          pinSpacing: true,
          anticipatePin: 1,
          invalidateOnRefresh: true,
        }
      });

      // Card animations
      items.forEach((item) => {
        const card = item.querySelector(".card");
        const image = item.querySelector(".card img");

        gsap.set(card, { opacity: 0, scale: 0.6, y: 20 });
        gsap.set(image, { opacity: 0, scale: 0.6, y: 20 });

        gsap.timeline({
          scrollTrigger: {
            trigger: item,
            containerAnimation: horizontalTween,
            start: "left 75%",
            toggleActions: "play none none reverse",
          }
        }).to(card, {
          opacity: 1,
          scale: 1,
          y: 0,
          duration: 0.8,
          ease: "back.out(1.7)",
          transformOrigin: "left center"
        }).to(image, {
          opacity: 1,
          scale: 1,
          y: 0,
          duration: 0.8,
          ease: "back.out(1.7)",
          transformOrigin: "left center"
        }, "-=0.6");
      });
    },

    counter: function () {

      gsap.utils.toArray(".counter").forEach(counter => {
        const numberEl = counter.querySelector(".number");
        const endValue = parseInt(numberEl.dataset.number, 10);

        gsap.fromTo(
          numberEl,
          { innerText: 0 },
          {
            innerText: endValue,
            duration: 2,
            ease: "power3.out",
            snap: { innerText: 1 },
            scrollTrigger: {
              trigger: counter,
              start: "top 80%",
              once: true
            },
            onUpdate: function () {
              numberEl.innerText = String(Math.floor(numberEl.innerText)).padStart(2, "0");
            }
          }
        );
      });
    },

    partnersSwiper: function () {
      const marqueeSwiper = new Swiper('.partners-swiper', {
        loop: true,
        slidesPerView: 6,
        spaceBetween: 0,
        speed: 5000, // higher = slower, smoother
        allowTouchMove: false,
        autoplay: {
          delay: 0,
          disableOnInteraction: false
        },
        breakpoints: {
          0: {
            slidesPerView: 2,
          },
          640: {
            slidesPerView: 3,
          },
          768: {
            slidesPerView: 4,
          },
          1024: {
            slidesPerView: 6,
          },
        },
      });
    },

    testimonialSwiper: function () {
      const testimonialSwiper = new Swiper(".testimonial-swiper", {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: true,
        autoplay: false,
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        breakpoints: {
          0: {
            slidesPerView: 1,
            spaceBetween: 16,
          },
          640: {
            slidesPerView: 1,
            spaceBetween: 20,
          },
          768: {
            slidesPerView: 2,
            spaceBetween: 24,
          },
          1024: {
            slidesPerView: 3,
            spaceBetween: 30,
          },
        },
      });
    },

    caiCoinSwiper: function () {
      const testimonialSwiper = new Swiper(".cai-coins-swiper", {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: true,
        // autoplay: {
        //   delay: 4000,
        //   disableOnInteraction: false,
        // },
        autoplay: false,
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        breakpoints: {
          0: {
            slidesPerView: 1,
            spaceBetween: 16,
          },
          640: {
            slidesPerView: 1,
            spaceBetween: 20,
          },
          768: {
            slidesPerView: 2,
            spaceBetween: 24,
          },
          1024: {
            slidesPerView: 3,
            spaceBetween: 30,
          },
        },
      });

      const slider = document.querySelector('.cai-mall-slider');
      if (!slider) return;

      new Swiper(slider, {
        slidesPerView: 1,
        spaceBetween: 16,
        speed: 800,
        loop: true,
        grabCursor: false,
        watchSlidesProgress: false,

        pagination: {
          el: '.swiper-pagination',
          clickable: true
        },

        // navigation: {
        //   nextEl: '.swiper-button-next',
        //   prevEl: '.swiper-button-prev'
        // },
      });
    },

    backtoTop :function(){
      const btn = document.getElementById('backTop');
      if (!btn) return;

      window.addEventListener('scroll', () => {
        if (window.scrollY > 600) {
          $(btn).addClass('show')
        } else {
          $(btn).removeClass('show')
        }
      });

      btn.addEventListener('click', () => {
        gsap.to(window, {
          duration: 0.2,
          scrollTo: { y: 0 },
          ease: 'power4.inOut'
        });
      });
    },

    tabs: function () {
      if (!document.querySelector('.tab-container')) return;

      const tabs = document.querySelectorAll('[data-tab]');
      const contents = document.querySelectorAll('.tab-content');
      const indicator = document.querySelector('.nav-indicator');

      /* ---------------------------
         INDICATOR
      --------------------------- */
      function setIndicator(el, animate = true) {
        const { offsetLeft, offsetWidth } = el;

        gsap.to(indicator, {
          x: offsetLeft,
          width: offsetWidth,
          duration: animate ? 0.8 : 0,
          ease: 'power3.out'
        });
      }

      /* ---------------------------
         ACTIVATE TAB
      --------------------------- */
      function activateTab(id, animate = true) {
        tabs.forEach(tab => {
          const isActive = tab.dataset.tab === id;
          tab.classList.toggle('active', isActive);

          if (isActive) setIndicator(tab, animate);
        });

        contents.forEach(content => {
          if (content.id === id) {
            content.classList.add('active');

            gsap.fromTo(
              content,
              { autoAlpha: 0, y: 100 },
              {
                autoAlpha: 1,
                y: 0,
                // duration: animate ? 0.45 : 0,
                duration: 0.8,
                ease: 'power2.out'
              }
            );
          } else {
            content.classList.remove('active');
            gsap.set(content, { autoAlpha: 0, y: 0 });
          }
        });
      }

      /* ---------------------------
         CLICK HANDLER
      --------------------------- */
      tabs.forEach(tab => {
        tab.addEventListener('click', e => {
          e.preventDefault();
          activateTab(tab.dataset.tab);
        });
      });

      /* ---------------------------
         INIT (FIRST TAB)
      --------------------------- */
      activateTab(tabs[0].dataset.tab, false);
    },
  };
  // LOAD Functions
  $(window).on("load", function () {

    window.addEventListener("load", () => {
      ScrollTrigger.refresh();
    });

    init.header();
    init.backtoTop();
    init.homeLoad();
    init.heroMouseParallax();
    init.homeScrolAnimation();
    init.scrollAnimation();
    init.ctoaAnimate();
    init.counter();
    init.partnersSwiper();
    init.testimonialSwiper();
    init.caiCoinSwiper();
    init.tabs();
    init.pageLoad();
    publications();
  });

  // LOAD and RESIZE Functions
  $(window).on("load resize", function () {
  });
});