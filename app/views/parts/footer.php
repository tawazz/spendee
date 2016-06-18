<div class="row">
<!-- Footer -->
        <footer class="text-center">
            <div class="footer-above">
                <div class="container">
                    <div class="row">
                        <div class="footer-col col-sm-4">
                            <h3>Contact Us!</h3>
                            <address>{{ address|raw }}</address>
                              <span><i class="fa fa-fw fa-envelope"></i></span> {{ email|raw }}
                        </div>
                        <div class="footer-col col-sm-4">
                            <h3>Around the Web</h3>
                            <ul class="list-inline">
                                <li>
                                    <a href="#" class="btn-social btn-outline"><i class="fa fa-4x fa-facebook wow tada"></i></a>
                                </li>
                                <li>
                                    <a href="#" class="btn-social btn-outline"><i class="fa fa-4x fa-twitter wow tada"></i></a>
                                </li>
                                <li>
                                    <a href="#" class="btn-social btn-outline"><i class="fa fa-4x fa-whatsapp wow tada"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="footer-col col-sm-4" >
                            <h3>Site Links</h3>
                            <ul style="list-style-type: none;">
                                <li><a href="{{baseUrl}}/">Home</i></a></li>
                                <li><a href="{{baseUrl}}/expenses">Expenses</i></a></li>
                                <li><a href="{{baseUrl}}/incomes">Incomes</i></a></li>
                                <li><a href="{{baseUrl}}/dashboard">DashBoard</i></a></li>
                                <li><a href="{{baseUrl}}/account">Account</i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-below">
                <div class="container">
                    <div class="col-sm-6">
                        Copyright &copy; {{ brand }}  {{ "now"|date("Y") }}
                    </div>
                    <div class="col-sm-6">
                        Website design by talented web developer<br> <a class="text-primary"href="http://www.tawazz.net/me">Tawanda Nyakudjga</a>
                    </div>
                </div>
            </div>
        </footer>
</div>
