{!! view_render_event('bagisto.shop.layout.footer.before') !!}

<!--
    The category repository is injected directly here because there is no way
    to retrieve it from the view composer, as this is an anonymous component.
-->
@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

<!--
    This code needs to be refactored to reduce the amount of PHP in the Blade
    template as much as possible.
-->
@php
  $channel = core()->getCurrentChannel();

  $customization = $themeCustomizationRepository->findOneWhere([
    'type' => 'footer_links',
    'status' => 1,
    'theme_code' => $channel->theme,
    'channel_id' => $channel->id,
  ]);
@endphp

<footer class="store-footer basic-footer" style="background-color: #f2f2f2 !important; color: black !important;">
  <div aria-label="footer" class="store-footer__inner">
    <div class="container">
      <div class="grid grid-cols-2 lg:grid-cols-6 lg:gap-6">
        <div class="mb-2.5 col-span-2 ">

          <a href="https://uae.matchperfumes.com/en/" class="footer-logo " aria-label="logo">
            <!-- <img src="./Best Sellers - Match Perfumes UAE_files/5XQkVJR3f2Iu9N4DvNhWcGvRkrPqirua0NVxyT2B.png"
              height="&quot;64&quot;" style=" height:64px;" alt="Perfume" class="img-fluid mx-auto lg:mx-[unset]"> -->
              <h1 class="mb-2.5">Perfume</h1>
          </a>
          <div class="flex items-left justify-left md:justify-start rtl:lg:ml-2 ltr:lg:mr-2 lg:mb-0" style="padding-top: 12px; padding-bottom: 15px;">
            <p class="text-sm">VAT Account Number
              :
              104786666800003</p>
          </div>
                  <div>
          <ul class="payment-methods">
            <li class="item-method">
              <img src="{{ asset('images/mada_mini.png') }}" alt="mada" width="50" height="30" class="payment-img">
            </li>
            <li class="item-method">
              <img src="{{ asset('images/credit_card_mini.png') }}" alt="credit_card" width="50" height="30"
                class="payment-img">
            </li>
            <li class="item-method">
              <img src="{{ asset('images/bank_mini.png') }}" alt="bank" width="50" height="30" class="payment-img">
            </li>
            <li class="item-method">
              <img src="{{ asset('images/apple_pay_mini.png') }}" alt="apple_pay" width="50" height="30"
                class="payment-img">
            </li>
            <li class="item-method">
              <img src="{{ asset('images/tamara_installment_mini.png') }}" alt="tamara_installment"
                style="width:35px; height:30px;" class="payment-img">
            </li>
            <li class="item-method">
              <img src="{{ asset('images/cod_mini.png') }}" alt="cod" width="50" height="30" class="payment-img">
            </li>
          </ul>

        </div>
        </div>


        <div class="mb-2.5 col-span-2">
          <div class="">
            <h3 class="footer-title ">Important Links</h3>
            <ul class="footer-list store-links-items">
              <li><a href="/page/about-us"
                  aria-label="itme" class="text-xs lg:text-base ">About Us</a></li>
              <li><a
                  href="https://uae.matchperfumes.com/en/%D8%B3%D9%8A%D8%A7%D8%B3%D8%A9-%D8%A7%D9%84%D8%AE%D8%B5%D9%88%D8%B5%D9%8A%D8%A9-%D9%88%D8%B3%D8%B1%D9%8A%D8%A9-%D8%A7%D9%84%D9%85%D8%B9%D9%84%D9%88%D9%85%D8%A7%D8%AA/page-1230610191"
                  aria-label="itme" class="text-xs lg:text-base ">Privacy Policy and Confidentiality of
                  Information</a></li>
              <li><a
                  href="https://uae.matchperfumes.com/en/%D8%B3%D9%8A%D8%A7%D8%B3%D8%A9-%D8%A7%D9%84%D8%A7%D8%B3%D8%AA%D8%A8%D8%AF%D8%A7%D9%84-%D8%A3%D9%88-%D8%A7%D9%84%D8%A7%D8%B3%D8%AA%D8%B1%D8%AC%D8%A7%D8%B9/page-1963187465"
                  aria-label="itme" class="text-xs lg:text-base ">Exchange, Refund and Cancellation policy</a></li>
              <li><a
                  href="https://uae.matchperfumes.com/en/%D8%A5%D8%AA%D9%81%D8%A7%D9%82%D9%8A%D8%A9-%D8%A7%D9%84%D8%A7%D8%B3%D8%AA%D8%AE%D8%AF%D8%A7%D9%85/page-280304395"
                  aria-label="itme" class="text-xs lg:text-base ">Usage agreement</a></li>
              <li><a
                  href="https://uae.matchperfumes.com/en/%D9%85%D8%A7%D9%87%D9%8A-%D8%A7%D9%84%D8%B9%D8%B7%D9%88%D8%B1-%D8%A7%D9%84%D9%85%D8%B3%D8%AA%D9%88%D8%AD%D8%A7%D8%A9/page-1653750292"
                  aria-label="itme" class="text-xs lg:text-base ">What are inspired perfumes and niche perfumes?</a>
              </li>
            </ul>
          </div>
        </div>
       
        <div class="mb-2.5 col-span-2">
    <div class="user-contacts lg:col-span-1 rounded_contacts">
        <h3 class="footer-title">Contact us</h3>
        <a href="https://uae.matchperfumes.com/en/whatsapp/send" class="links-contact" aria-label="contact" style="padding-top: 6px; display: block;">
            <i class="fa-brands fa-whatsapp text-white-500 text-xl" style="padding-right: 15px;"></i>
            <span class="text-unicode unicode break-all">+966125785690</span>
        </a>
        <a href="tel:920032057" class="links-contact" aria-label="contact" style="padding-top: 6px; display: block;">
            <i class="fa-solid fa-phone text-white-600 text-xl" style="padding-right: 15px;"></i>
            <span class="text-unicode unicode break-all">920032057</span>
        </a>
    </div>
</div>
        
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container py-4">
      <div
        class="flex items-center justify-center lg:justify-between flex-wrap lg:flex-nowrap lg:flex-row flex-col-reverse">
        <div class="text-sm py-2.5 footer-rights">
          <p class="text-gray-400 mb-2.5 md:mb-0">Copyright | 2025
            <a href="https://uae.matchperfumes.com/en" class="hover:text-primary" target="_blank"
              rel="noreferrer">Perfumes</a>
          </p>
        </div>


        <div class="py-2.5 lg:py-0">
          <ul class="flex -mx-1 rounded_contacts">
            <li class="mx-1">
              <a href="https://www.instagram.com/matchperfumes.sa" target="_blank" title="Instagram"
                aria-label="Instagram" class="social-link">
                <i class="fab fa-instagram"></i>
              </a>
            </li>
            <li class="mx-1">
              <a href="https://x.com/matchperfumesa" target="_blank" title="X / Twitter" aria-label="X / Twitter"
                class="social-link">
                <i class="fab fa-x-twitter"></i>
              </a>
            </li>
            <li class="mx-1">
              <a href="https://www.snapchat.com/add/matchperfumes" target="_blank" title="Snapchat"
                aria-label="Snapchat" class="social-link">
                <i class="fab fa-snapchat"></i>
              </a>
            </li>
            <li class="mx-1">
              <a href="https://www.tiktok.com/@matchperfume.com" target="_blank" title="TikTok" aria-label="TikTok"
                class="social-link">
                <i class="fab fa-tiktok"></i>
              </a>
            </li>
            <li class="mx-1">
              <a href="https://www.youtube.com/@MatchPerfumes" target="_blank" title="YouTube" aria-label="YouTube"
                class="social-link">
                <i class="fab fa-youtube"></i>
              </a>
            </li>
            <li class="mx-1">
              <a href="https://www.facebook.com/profile.php?id=61552675531226" target="_blank" title="Facebook"
                aria-label="Facebook" class="social-link">
                <i class="fab fa-facebook-f"></i>
              </a>
            </li>
          </ul>
        </div>

      </div>
    </div>
  </div>

  <span
    class="hidden w-1/2 mt-4 md:mt-12 sm:w-auto bottom-2 rtl:pl-24 ltr:pr-24 text-storeBG pointer-events-none bg-storeBG from-storeBG h-16 w-16 aspect-[3/4] lg:!w-[60%] lg:!w-[40%] object-fill object-bottom object-left object-right object-none object-top opacity-40"></span>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}

<!--apply footer background-color-->
<script>
document.addEventListener('DOMContentLoaded', function() {

    const footer = document.querySelector('footer.store-footer.basic-footer');
    if (footer) {
        footer.style.backgroundColor = '#f2f2f2';
        footer.style.color = 'black';
        
        const allChildren = footer.querySelectorAll('*');
        allChildren.forEach(child => {
            child.style.backgroundColor = '#f2f2f2';
            child.style.color = 'black';
        });
    }
});
</script>