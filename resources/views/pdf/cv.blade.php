<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>CV - {{ $content['hero_title'] }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            color: #222;
            margin: 0;
            padding: 0;
            text-align: justify;
        }

        .container {
            max-width: 800px;
            margin: 0;
            padding: 0;
        }

        .hero-title {
            color: #0f766e;
            font-size: 2.2em;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .contact {
            font-size: 1em;
            color: #444;
            margin-bottom: 10px;
        }

        .contact a {
            color: #0f766e;
            text-decoration: none;
        }

        a {
            color: #0f766e;
        }

        .section-title {
            color: #0f766e;
            font-size: 1.3em;
            margin-top: 32px;
            margin-bottom: 14px;
            border-left: 4px solid #0f766e;
            padding-left: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .schools,
        .skills,
        .experiences {
            margin-bottom: 18px;
        }

        .school-item,
        .experience-item {
            margin-bottom: 12px;
        }

        .school-title {
            font-weight: bold;
            color: #222;
        }

        .school-location {
            margin-left: 7px;
        }

        .school-location,
        .experience-location {
            color: #666;
            font-size: 0.98em;
        }

        .graduation {
            color: #0f766e;
            font-size: 0.98em;
            margin-bottom: 3px;
        }

        .skills-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
        }

        .skills-list li {
            background: #e0f2f1;
            color: #0f766e;
            border-radius: 16px;
            padding: 5px 16px;
            margin: 0 10px 8px 0;
            font-size: 1em;
        }

        .experience-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .experience-dates {
            color: #0f766e;
            font-size: 1em;
            font-weight: bold;
        }

        .experience-job {
            font-size: 1.15em;
            font-weight: bold;
            color: #222;
        }

        .experience-company {
            color: #0f766e;
            font-weight: bold;
        }

        .experience-description {
            margin-top: 4px;
            color: #444;
            font-size: 1em;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.95em;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="hero-title">{{ $content['hero_title'] }}</h1>
            <div class="contact">
                {{ $address['address_line_1'] }}<br>
                @if(!empty($address['address_line_2']))
                {{ $address['address_line_2'] }}<br>
                @endif
                {{ $address['city'] }} {{ $address['province'] }} {{ $address['zip_code'] }}<br>
                @if(!empty($address['phone']))
                {{ $address['phone'] }}<br>
                @endif
                <a href="mailto:{{ $address['email'] }}">{{ $address['email'] }}</a><br>
                <span>{{ $address['app_url'] }}</span>
            </div>
            <div style="margin-top: 10px;">
                {!! $content['hero_description'] !!}
            </div>
        </div>

        <!-- Formation -->
        <div class="schools">
            <div class="section-title">Formation</div>
            @foreach ($schools as $school)
            <div class="school-item">
                <span class="school-title">{{ \Carbon\Carbon::parse($school['date'])->format('Y') }} - {{ $school['school'] }}</span>
                <span class="school-location">{{ $school['location'] }}</span>
                <div class="graduation">{!! $school['graduation'] !!}</div>
            </div>
            @endforeach
        </div>

        <!-- Compétences -->
        <div class="skills">
            <div class="section-title">Compétences</div>
            <ul class="skills-list">
                @foreach ($skills as $skill)
                <li>{{ $skill['label'] }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Expériences -->
        <div class="experiences">
            <div class="section-title">Expériences</div>
            @foreach($experiences as $index => $experience)
            <div class="experience-item">
                <div class="experience-header">
                    <span class="experience-dates">
                        De {{ \Carbon\Carbon::parse($experience['begin_at'])->translatedFormat('F Y') }}
                        @if($experience['end_at'])
                        à {{ \Carbon\Carbon::parse($experience['end_at'])->translatedFormat('F Y') }}
                        @else
                        à aujourd'hui
                        @endif
                    </span>
                    <span class="experience-location">&nbsp;-&nbsp;{{ $experience['location'] }}</span>
                </div>
                <div class="experience-job">
                    @if(!empty($experience['company']))
                    <span class="experience-company">{{ $experience['company'] }}</span> ({{ $experience['job'] }})
                    @else
                    {{ $experience['job'] }}
                    @endif
                </div>
                <div class="experience-description">
                    {!! $experience['description'] !!}
                </div>
            </div>
            @endforeach
        </div>

        <!-- Footer -->
        <div class="footer">
            Généré le {{ \Carbon\Carbon::now()->format('d/m/Y') }}
        </div>
    </div>
</body>

</html>
