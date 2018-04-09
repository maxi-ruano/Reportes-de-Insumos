@extends('layouts.templeate')
@section('titlePage', 'Generar Cenat')
@section('content')
<!-- page content -->

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
          @include('safit.botoneraPrecheck')
          <div class="clearfix"></div>
      </div>
      <div class="x_content">
        {!! Form::open(['route' => 'consultarBoletaPago', 'id'=>'consultarBoletaPago', 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'role' => 'form', 'files' => true ]) !!}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Codigo de pago electronico<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="number" class="form-control" name="bop_cb" aria-describedby="codigoPagoElectronico" placeholder="Ejem ... 1065468798">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Centro Emisor<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="cem_id" class='select2_single form-control' data-placeholder='Seleccionar Centro Emisor'>
                @foreach ($centrosEmisores as $key => $value)
                    <option value="{{ $value->safit_cem_id }}"> {{ $value->name }} </option>
                @endforeach
                </select>
              </div>
            </div>
            </fieldset>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="YYYY-MM-DD">
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <button type="submit" class="btn btn-primary btn-block">Buscar Boleta Pago</button>
              </div>
            </div>

        {{ Form::close() }}
        <div class="clearfix"></div>
        @if (isset($boleta))
          {!! Form::open(['route' => 'generarCenat', 'id'=>'generarCenat', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true ]) !!}
              {!! Form::hidden('nro_doc', isset($boleta) ? $boleta->nro_doc : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('tipo_doc', isset($boleta) ? $boleta->tipo_doc : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('sexo', isset($boleta) ? $boleta->sexo : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('nombre', isset($boleta) ? $boleta->nombre : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('apellido', isset($boleta) ? $boleta->apellido : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('bop_id', isset($boleta) ? $boleta->bop_id : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('bop_cb', isset($boleta) ? $boleta->bop_cb : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('bop_monto', isset($boleta) ? $boleta->bop_monto : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('bop_fec_pag', isset($boleta) ? $boleta->bop_fec_pag : null, ['class' => 'form-control']) !!}
              {!! Form::hidden('cem_id', isset($boleta) ? $boleta->cem_id : null, ['class' => 'form-control']) !!}
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label>Nombre : {{ $boleta->nombre }} {{ $boleta->apellido }}</label>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <label>Numero Documento : {{ $boleta->nro_doc }}</label>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Fecha Nacimiento :<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="fecha_nacimiento" type="text" data-date-format='yy-mm-dd' class="form-control has-feedback-left" id="single_cal4" placeholder="First Name" aria-describedby="inputSuccess2Status4">
                  <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                </div>
	      </div>
		  <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nacionalidad :<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="nacionalidad" class='select2_single form-control' data-placeholder='Seleccionar Nacionalidad'>
                  <option value="1">AFGANISTAN</option>
                  <option value="2">ISLAS GLAND</option>
                  <option value="3">ALBANIA</option>
                  <option value="4">ALEMANIA</option>
                  <option value="5">ANDORRA</option>
                  <option value="6">ANGOLA</option>
                  <option value="7">ANGUILLA</option>
                  <option value="8">ANTARTIDA</option>
                  <option value="9">ANTIGUA Y BARBUDA</option>
                  <option value="10">ANTILLAS HOLANDESAS</option>
                  <option value="11">ARABIA SAUDITA</option>
                  <option value="12">ARGELIA</option>
                  <option value="13" selected>ARGENTINA</option>
                  <option value="14">ARMENIA</option>
                  <option value="15">ARUBA</option>
                  <option value="16">AUSTRALIA</option>
                  <option value="17">AUSTRIA</option>
                  <option value="18">AZERBAIYAN</option>
                  <option value="19">BAHAMAS</option>
                  <option value="20">BAHREIN</option>
                  <option value="21">BANGLADESH</option>
                  <option value="22">BARBADOS</option>
                  <option value="23">BIELORRUSIA</option>
                  <option value="24">BELGICA</option>
                  <option value="25">BELICE</option>
                  <option value="26">BENIN</option>
                  <option value="27">BERMUDAS</option>
                  <option value="28">BUTAN</option>
                  <option value="29">BOLIVIA</option>
                  <option value="30">BOSNIA Y HERZEGOVINA</option>
                  <option value="31">BOTSWANA</option>
                  <option value="32">ISLA BOUVET</option>
                  <option value="33">BRASIL</option>
                  <option value="34">BRUNEI</option>
                  <option value="35">BULGARIA</option>
                  <option value="36">BURKINA FASO</option>
                  <option value="37">BURUNDI</option>
                  <option value="38">CABO VERDE</option>
                  <option value="39">ISLAS CAIMAN</option>
                  <option value="40">CAMBOYA</option>
                  <option value="41">CAMERUN</option>
                  <option value="42">CANADA</option>
                  <option value="43">REPUBLICA CENTROAFRICANA</option>
                  <option value="44">CHAD</option>
                  <option value="45">REPUBLICA CHECA</option>
                  <option value="46">CHILE</option>
                  <option value="47">CHINA</option>
                  <option value="48">CHIPRE</option>
                  <option value="49">ISLA DE NAVIDAD</option>
                  <option value="50">CIUDAD DEL VATICANO</option>
                  <option value="51">ISLAS COCOS</option>
                  <option value="52">COLOMBIA</option>
                  <option value="53">COMORAS</option>
                  <option value="54">REPUBLICA DEMOCRATICA DEL CONGO</option>
                  <option value="55">CONGO</option>
                  <option value="56">ISLAS COOK</option>
                  <option value="57">REPUBLICA POPULAR DEMOCRATICA COREA (NORTE)</option>
                  <option value="58">REPUBLICA DE COREA (SUR)</option>
                  <option value="59">COSTA DE MARFIL</option>
                  <option value="60">COSTA RICA</option>
                  <option value="61">CROACIA</option>
                  <option value="62">CUBA</option>
                  <option value="63">DINAMARCA</option>
                  <option value="64">DOMINICA</option>
                  <option value="65">REPUBLICA DOMINICANA</option>
                  <option value="66">ECUADOR</option>
                  <option value="67">EGIPTO</option>
                  <option value="68">EL SALVADOR</option>
                  <option value="69">EMIRATOS ARABES UNIDOS</option>
                  <option value="70">ERITREA</option>
                  <option value="71">ESLOVAQUIA</option>
                  <option value="72">ESLOVENIA</option>
                  <option value="73">ESPAÑ</option>
                  <option value="74">ISLAS ULTRAMARINAS DE ESTADOS UNIDOS</option>
                  <option value="75">ESTADOS UNIDOS DE NORTEAMERICA</option>
                  <option value="76">ESTONIA</option>
                  <option value="77">ETIOPIA</option>
                  <option value="78">ISLAS FEROE</option>
                  <option value="79">FILIPINAS</option>
                  <option value="80">FINLANDIA</option>
                  <option value="81">FIYI</option>
                  <option value="82">FRANCIA</option>
                  <option value="83">GABON</option>
                  <option value="84">GAMBIA</option>
                  <option value="85">GEORGIA</option>
                  <option value="86">ISLAS GEORGIAS DEL SUR Y SANDWICH DEL SUR</option>
                  <option value="87">GHANA</option>
                  <option value="88">GIBRALTAR</option>
                  <option value="89">GRANADA</option>
                  <option value="90">GRECIA</option>
                  <option value="91">GROENLANDIA</option>
                  <option value="92">GUADALUPE</option>
                  <option value="93">GUAM</option>
                  <option value="94">GUATEMALA</option>
                  <option value="95">GUAYANA FRANCESA</option>
                  <option value="96">GUINEA</option>
                  <option value="97">GUINEA ECUATORIAL</option>
                  <option value="98">GUINEA-BISSAU</option>
                  <option value="99">GUYANA</option>
                  <option value="100">HAITI</option>
                  <option value="101">ISLAS HEARD Y MCDONALD</option>
                  <option value="102">HONDURAS</option>
                  <option value="103">HONG KONG</option>
                  <option value="104">HUNGRIA</option>
                  <option value="105">INDIA</option>
                  <option value="106">INDONESIA</option>
                  <option value="107">IRAN</option>
                  <option value="108">IRAQ</option>
                  <option value="109">IRLANDA</option>
                  <option value="110">ISLANDIA</option>
                  <option value="111">ISRAEL</option>
                  <option value="112">ITALIA</option>
                  <option value="113">JAMAICA</option>
                  <option value="114">JAPON</option>
                  <option value="115">JORDANIA</option>
                  <option value="116">KAZAJSTAN</option>
                  <option value="117">KENIA</option>
                  <option value="118">KIRGUISTAN</option>
                  <option value="119">KIRIBATI</option>
                  <option value="120">KUWAIT</option>
                  <option value="121">REPUBLICA DEMOCRATICA POPULAR LAO</option>
                  <option value="122">LESOTHO</option>
                  <option value="123">LETONIA</option>
                  <option value="124">LIBANO</option>
                  <option value="125">LIBERIA</option>
                  <option value="126">LIBIA</option>
                  <option value="127">LIECHTENSTEIN</option>
                  <option value="128">LITUANIA</option>
                  <option value="129">LUXEMBURGO</option>
                  <option value="130">MACAO</option>
                  <option value="131">MACEDONIA</option>
                  <option value="132">MADAGASCAR</option>
                  <option value="133">MALASIA</option>
                  <option value="134">MALAWI</option>
                  <option value="135">MALDIVAS</option>
                  <option value="136">MAL</option>
                  <option value="137">MALTA</option>
                  <option value="138">ISLAS MALVINAS</option>
                  <option value="139">ISLAS MARIANAS DEL NORTE</option>
                  <option value="140">MARRUECOS</option>
                  <option value="141">ISLAS MARSHALL</option>
                  <option value="142">MARTINICA</option>
                  <option value="143">MAURICIO</option>
                  <option value="144">MAURITANIA</option>
                  <option value="145">MAYOTTE</option>
                  <option value="146">MEXICO</option>
                  <option value="147">ESTADOS FEDERATIVOS DE MICRONESIA</option>
                  <option value="148">MOLDAVIA</option>
                  <option value="149">MONACO</option>
                  <option value="150">MONGOLIA</option>
                  <option value="151">MONTSERRAT</option>
                  <option value="152">MOZAMBIQUE</option>
                  <option value="153">BIRMANIA</option>
                  <option value="154">NAMIBIA</option>
                  <option value="155">NAURU</option>
                  <option value="156">NEPAL</option>
                  <option value="157">NICARAGUA</option>
                  <option value="158">NIGERIA</option>
                  <option value="159">NIGERIA</option>
                  <option value="160">NIUE</option>
                  <option value="161">ISLA NORFOLK</option>
                  <option value="162">NORUEGA</option>
                  <option value="163">NUEVA CALEDONIA</option>
                  <option value="164">NUEVA ZELANDA</option>
                  <option value="165">OMAN</option>
                  <option value="166">PAISES BAJOS</option>
                  <option value="167">PAKISTAN</option>
                  <option value="168">PALAOS</option>
                  <option value="169">PALESTINA</option>
                  <option value="170">PANAMA</option>
                  <option value="171">PAPUA NUEVA GUINEA</option>
                  <option value="172">PARAGUAY</option>
                  <option value="173">PERU</option>
                  <option value="174">ISLAS PITCAIRN</option>
                  <option value="175">POLINESIA FRANCESA</option>
                  <option value="176">POLONIA</option>
                  <option value="177">PORTUGAL</option>
                  <option value="178">PUERTO RICO</option>
                  <option value="179">QATAR</option>
                  <option value="180">REINO UNIDO</option>
                  <option value="181">REUNION</option>
                  <option value="182">RUANDA</option>
                  <option value="183">RUMANIA</option>
                  <option value="184">RUSIA</option>
                  <option value="185">SAHARA OCCIDENTAL</option>
                  <option value="186">ISLAS SALOMON</option>
                  <option value="187">ESTADO INDEPENDIENTE DE SAMOA</option>
                  <option value="188">SAMOA AMERICANA</option>
                  <option value="189">SAN CRISTOBAL Y NEVIS</option>
                  <option value="190">SAN MARINO</option>
                  <option value="191">SAN PEDRO Y MIQUELIN</option>
                  <option value="192">SAN VICENTE Y LAS GRANADINAS</option>
                  <option value="193">SANTA HELENA</option>
                  <option value="194">SANTA LUCIA</option>
                  <option value="195">REPUBLICA DEMOCRATICA DE SANTO TOME Y PRINCIPE</option>
                  <option value="196">SENEGAL</option>
                  <option value="197">SERBIA</option>
                  <option value="198">SEYCHELLES</option>
                  <option value="199">SIERRA LEONA</option>
                  <option value="200">SINGAPUR</option>
                  <option value="201">REPUBLICA ARABE SIRIA</option>
                  <option value="202">SOMALIA</option>
                  <option value="203">SRI LANKA</option>
                  <option value="204">SWAZILANDIA</option>
                  <option value="205">SUDAFRICA</option>
                  <option value="206">SUDAN</option>
                  <option value="207">SUECIA</option>
                  <option value="208">SUIZA</option>
                  <option value="209">SURINAME</option>
                  <option value="210">SVALBARD Y JAN MAYEN</option>
                  <option value="211">TAILANDIA</option>
                  <option value="212">TAIWAN</option>
                  <option value="213">REPUBLICA UNIDA DE TANZANIA</option>
                  <option value="214">TAYIKISTAN</option>
                  <option value="215">TERRITORIO BRITANICO DEL OCEANO INDICO</option>
                  <option value="216">TERRITORIOS AUSTRALES FRANCESES</option>
                  <option value="217">REPUBLICA DEMOCRATICA DE TIMOR ORIENTAL</option>
                  <option value="218">TOGO</option>
                  <option value="219">TOKELAU</option>
                  <option value="220">REINO DE TONGA</option>
                  <option value="221">TRINIDAD Y TOBAGO</option>
                  <option value="222">TUNEZ</option>
                  <option value="223">ISLAS TURCAS Y CAICOS</option>
                  <option value="224">TURKMENISTAN</option>
                  <option value="225">TURQUIA</option>
                  <option value="226">TUVALU</option>
                  <option value="227">UCRANIA</option>
                  <option value="228">UGANDA</option>
                  <option value="229">URUGUAY</option>
                  <option value="230">UZBEKISTAN</option>
                  <option value="231">VANUATU</option>
                  <option value="232">VENEZUELA</option>
                  <option value="233">REPUBLICA SOCIALISTA DE VIETNAM</option>
                  <option value="234">ISLAS VIRGENES BRITANICAS</option>
                  <option value="235">ISLAS VIRGENES DE LOS ESTADOS UNIDOS</option>
                  <option value="236">ISLAS WALLIS Y FUTUNA</option>
                  <option value="237">REPUBLICA DEL YEMEN</option>
                  <option value="238">REPUBLICA DE YIBUTI</option>
                  <option value="239">REPUBLICA DE ZAMBIA</option>
                  <option value="240">ZIMBABWE</option>
                  <option value="241">MONTENEGRO</option>
                  <option value="242">ESCOCIA</option>
                  <option value="243">GALES</option>
                  <option value="244">YUGOSLAVIA</option>
                </select>
                </div>
              </div>
              </fieldset>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <button type="submit" class="btn btn-primary btn-block">Generar Certificado Virtual</button>
                </div>
              </div>
          {{ Form::close() }}
        @endif
        <div class="form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12">
          </label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            @if (isset($error))
              <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <strong>{{ $error }}</strong>
              </div>
            @endif
            @if (isset($success))
              <div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <strong>{{ $success }}</strong>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- /page content -->
@endsection

@push('scripts')
  <script src="{{ asset('vendors/jquery/dist/jquery.min.js')}}"></script>
  <!-- Bootstrap -->
  <script src="{{ asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="{{ asset('vendors/moment/min/moment.min.js')}}"></script>
  <script src="{{ asset('vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
  <!-- Custom Theme Scripts -->
  <script src="{{ asset('build/js/custom.min.js')}}"></script>
  <script>
    $('#single_cal4').daterangepicker({
      singleDatePicker: true,
      singleClasses: "picker_4",
      locale: {
            format: 'YYYY-MM-DD'
        }
    }, function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    });
  </script>
@endpush

@section('css')
  <!-- bootstrap-daterangepicker -->
  <link href="{{ asset('vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@endsection
