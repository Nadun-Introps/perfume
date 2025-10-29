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

<footer class="mp-footer">
    <div class="mp-footer__main">
        <div class="mp-container">
            <div class="mp-footer__columns">
                <!-- First Column: Logo, VAT Info, Payment Methods -->
                <div class="mp-footer__column mp-footer__column--left">
                    <a href="{{ route('shop.home.index') }}" class="mp-footer__logo" aria-label="logo">
                        <h1 class="mp-footer__logo-text">SwissDuft</h1>
                    </a>
                    <div class="mp-footer__vat-info">
                        <p>VAT Account Number : 104786666800003</p>
                    </div>
                    <div class="mp-footer__payment-methods">
                        <ul class="mp-payment-list">
                            <li class="mp-payment-item">
                                <img src="{{ asset('images/mada_mini.png') }}" alt="mada" width="40"
                                    height="20" class="mp-payment-img">
                            </li>
                            <li class="mp-payment-item">
                                <img src="{{ asset('images/credit_card_mini.png') }}" alt="credit_card" width="40"
                                    height="20" class="mp-payment-img">
                            </li>
                            <li class="mp-payment-item">
                                <img src="{{ asset('images/bank_mini.png') }}" alt="bank" width="40"
                                    height="20" class="mp-payment-img">
                            </li>
                            <li class="mp-payment-item">
                                <img src="{{ asset('images/apple_pay_mini.png') }}" alt="apple_pay" width="40"
                                    height="20" class="mp-payment-img">
                            </li>
                            <li class="mp-payment-item">
                                <img src="{{ asset('images/tamara_installment_mini.png') }}" alt="tamara_installment"
                                    width="25" height="20" class="mp-payment-img">
                            </li>
                            <li class="mp-payment-item">
                                <img src="{{ asset('images/cod_mini.png') }}" alt="cod" width="40"
                                    height="20" class="mp-payment-img">
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Second Column: Important Links -->
                <div class="mp-footer__column mp-footer__column--middle">
                    <h3 class="mp-footer__title">Important Links</h3>
                    <ul class="mp-footer__links">
                        <li class="mp-footer__link-item">
                            <a href="/page/about-us" aria-label="About Us" class="mp-footer__link">About Us</a>
                        </li>
                        <li class="mp-footer__link-item">
                            <a href=""
                                aria-label="Privacy Policy" class="mp-footer__link">Privacy Policy and Confidentiality
                                of Information</a>
                        </li>
                        <li class="mp-footer__link-item">
                            <a href=""
                                aria-label="Exchange Policy" class="mp-footer__link">Exchange, Refund and Cancellation
                                policy</a>
                        </li>
                        <li class="mp-footer__link-item">
                            <a href=""
                                aria-label="Usage Agreement" class="mp-footer__link">Usage agreement</a>
                        </li>
                        <li class="mp-footer__link-item">
                            <a href=""
                                aria-label="Perfume Info" class="mp-footer__link">What are inspired perfumes and niche
                                perfumes?</a>
                        </li>
                    </ul>
                </div>

                <!-- Third Column: Contact Information -->
                <div class="mp-footer__column mp-footer__column--right">
                    <h3 class="mp-footer__title">Contact us</h3>
                    <div class="mp-footer__contacts">
                        <a href="https://uae.matchperfumes.com/en/whatsapp/send" class="mp-contact-link"
                            aria-label="WhatsApp">
                            <i class="fa-brands fa-whatsapp mp-contact-icon"></i>
                            <span class="mp-contact-text">+966125785690</span>
                        </a>
                        <a href="tel:920032057" class="mp-contact-link" aria-label="Phone">
                            <i class="fa-solid fa-phone mp-contact-icon"></i>
                            <span class="mp-contact-text">920032057</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom Section -->
    <div class="mp-footer__bottom">
        <div class="mp-container">
            <div class="mp-footer__bottom-content">
                <div class="mp-footer__copyright">
                    <p>Developed by | 2025
                        <a href="https://introps.com/" class="mp-footer__copyright-link" target="_blank"
                            rel="noreferrer">Introps IT</a>
                    </p>
                </div>
                <div class="mp-footer__social">
                    <ul class="mp-social-list">
                        <li class="mp-social-item">
                            <a href="https://www.instagram.com/matchperfumes.sa" target="_blank" title="Instagram"
                                aria-label="Instagram" class="mp-social-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </li>
                        <li class="mp-social-item">
                            <a href="https://x.com/matchperfumesa" target="_blank" title="X / Twitter"
                                aria-label="X / Twitter" class="mp-social-link">
                                <i class="fab fa-x-twitter"></i>
                            </a>
                        </li>
                        <li class="mp-social-item">
                            <a href="https://www.snapchat.com/add/matchperfumes" target="_blank" title="Snapchat"
                                aria-label="Snapchat" class="mp-social-link">
                                <i class="fab fa-snapchat"></i>
                            </a>
                        </li>
                        <li class="mp-social-item">
                            <a href="https://www.tiktok.com/@matchperfume.com" target="_blank" title="TikTok"
                                aria-label="TikTok" class="mp-social-link">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        </li>
                        <li class="mp-social-item">
                            <a href="https://www.youtube.com/@MatchPerfumes" target="_blank" title="YouTube"
                                aria-label="YouTube" class="mp-social-link">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </li>
                        <li class="mp-social-item">
                            <a href="https://www.facebook.com/profile.php?id=61552675531226" target="_blank"
                                title="Facebook" aria-label="Facebook" class="mp-social-link">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}
