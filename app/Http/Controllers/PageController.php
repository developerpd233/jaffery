<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Models\Page;
use App\Mail\ContactMail;
use App\Models\Profession;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

use App\Models\Contest;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\WinnerMail;

class PageController extends Controller
{
    public function index($slug = 'home')
    {
        $page = Page::where(['slug' => $slug, 'status' => 'ACTIVE'])->firstOrFail();

        return view("page", compact('page'));
    }

    public function faq()
    {
        return view("faq");
    }

    public function contact(Request $request)
    {
        $emails = ['theoflas@yahoo.com', 'contact@pose2post.com'];

        try {
            // Mail::to($request->email)->send(new ContactMail($request->all())); 
            Mail::to($emails)->send(new ContactMail($request->all()));    
        } 
        catch (\Throwable $th) {
           
        }
        
        return back()->with('success','Email sent successfully.');
    }

    public function uploadProfessions()
    {
        $arr = [
            "Modeling",
            "Entertainment",
            "Academic librarian",
            "Accountant",
            "Accounting technician",
            "Actuary",
            "Adult nurse",
            "Advertising account executive",
            "Advertising account planner",
            "Advertising copywriter",
            "Advice worker",
            "Advocate (Scotland)",
            "Aeronautical engineer",
            "Agricultural consultant",
            "Agricultural manager",
            "Aid worker/humanitarian worker",
            "Air traffic controller",
            "Airline cabin crew",
            "Amenity horticulturist",
            "Analytical chemist",
            "Animal nutritionist",
            "Animator",
            "Archaeologist",
            "Architect",
            "Architectural technologist",
            "Archivist",
            "Armed forces officer",
            "Aromatherapist",
            "Art therapist",
            "Arts administrator",
            "Auditor",
            "Automotive engineer",
            "Barrister",
            "Barrister’s clerk",
            "Bilingual secretary",
            "Biomedical engineer",
            "Biomedical scientist",
            "Biotechnologist",
            "Brand manager",
            "Broadcasting presenter",
            "Building control officer/surveyor",
            "Building services engineer",
            "Building surveyor",
            "Camera operator",
            "Careers adviser (higher education)",
            "Careers adviser",
            "Careers consultant",
            "Cartographer",
            "Catering manager",
            "Charities administrator",
            "Charities fundraiser",
            "Chemical (process) engineer",
            "Child psychotherapist",
            "Children's nurse",
            "Chiropractor",
            "Civil engineer",
            "Civil Service administrator",
            "Clinical biochemist",
            "Clinical cytogeneticist",
            "Clinical microbiologist",
            "Clinical molecular geneticist",
            "Clinical research associate",
            "Clinical scientist - tissue typing",
            "Clothing and textile technologist",
            "Colour technologist",
            "Commercial horticulturist",
            "Commercial/residential/rural surveyor",
            "Commissioning editor",
            "Commissioning engineer",
            "Commodity broker",
            "Communications engineer",
            "Community arts worker",
            "Community education officer",
            "Community worker",
            "Company secretary",
            "Computer sales support",
            "Computer scientist",
            "Conference organiser",
            "Consultant",
            "Consumer rights adviser",
            "Control and instrumentation engineer",
            "Corporate banker",
            "Corporate treasurer",
            "Counsellor",
            "Courier/tour guide",
            "Court reporter/verbatim reporter",
            "Credit analyst",
            "Crown Prosecution Service lawyer",
            "Crystallographer",
            "Curator",
            "Customs officer",
            "Cyber security specialist",
            "Dance movement therapist",
            "Data analyst",
            "Data scientist",
            "Data visualisation analyst",
            "Database administrator",
            "Debt/finance adviser",
            "Dental hygienist",
            "Dentist",
            "Design engineer",
            "Dietitian",
            "Diplomatic service",
            "Doctor (general practitioner, GP)",
            "Doctor (hospital)",
            "Dramatherapist",
            "Economist",
            "Editorial assistant",
            "Education administrator",
            "Electrical engineer",
            "Electronics engineer",
            "Employment advice worker",
            "Energy conservation officer",
            "Engineering geologist",
            "Environmental education officer",
            "Environmental health officer",
            "Environmental manager",
            "Environmental scientist",
            "Equal opportunities officer",
            "Equality and diversity officer",
            "Ergonomist",
            "Estate agent",
            "European Commission administrators",
            "Exhibition display designer",
            "Exhibition organiser",
            "Exploration geologist",
            "Facilities manager",
            "Field trials officer",
            "Financial manager",
            "Firefighter",
            "Fisheries officer",
            "Fitness centre manager",
            "Food scientist",
            "Food technologist",
            "Forensic scientist",
            "Geneticist",
            "Geographical information systems manager",
            "Geomatics/land surveyor",
            "Government lawyer",
            "Government research officer",
            "Graphic designer",
            "Health and safety adviser",
            "Health and safety inspector",
            "Health promotion specialist",
            "Health service manager",
            "Health visitor",
            "Herbalist",
            "Heritage manager",
            "Higher education administrator",
            "Higher education advice worker",
            "Homeless worker",
            "Horticultural consultant",
            "Hotel manager",
            "Housing adviser",
            "Human resources officer",
            "Hydrologist",
            "Illustrator",
            "Immigration officer",
            "Immunologist",
            "Industrial/product designer",
            "Information scientist",
            "Information systems manager",
            "Information technology/software trainers",
            "Insurance broker",
            "Insurance claims inspector",
            "Insurance risk surveyor",
            "Insurance underwriter",
            "Interpreter",
            "Investment analyst",
            "Investment banker - corporate finance",
            "Investment banker – operations",
            "Investment fund manager",
            "IT consultant",
            "IT support analyst",
            "Journalist",
            "Laboratory technician",
            "Land-based engineer",
            "Landscape architect",
            "Learning disability nurse",
            "Learning mentor",
            "Lecturer (adult education)",
            "Lecturer (further education)",
            "Lecturer (higher education)",
            "Legal executive",
            "Leisure centre manager",
            "Licensed conveyancer",
            "Local government administrator",
            "Local government lawyer",
            "Logistics/distribution manager",
            "Magazine features editor",
            "Magazine journalist",
            "Maintenance engineer",
            "Management accountant",
            "Manufacturing engineer",
            "Manufacturing machine operator",
            "Manufacturing toolmaker",
            "Marine scientist",
            "Market research analyst",
            "Market research executive",
            "Marketing account manager",
            "Marketing assistant",
            "Marketing executive",
            "Marketing manager (social media)",
            "Materials engineer",
            "Materials specialist",
            "Mechanical engineer",
            "Media analyst",
            "Media buyer",
            "Media planner",
            "Medical physicist",
            "Medical representative",
            "Mental health nurse",
            "Metallurgist",
            "Meteorologist",
            "Microbiologist",
            "Midwife",
            "Mining engineer",
            "Mobile developer",
            "Multimedia programmer",
            "Multimedia specialists",
            "Museum education officer",
            "Museum/gallery exhibition officer",
            "Music therapist",
            "Nanoscientist",
            "Nature conservation officer",
            "Naval architect",
            "Network administrator",
            "Nurse",
            "Nutritional therapist",
            "Nutritionist",
            "Occupational therapist",
            "Oceanographer",
            "Office manager",
            "Operational researcher",
            "Orthoptist",
            "Outdoor pursuits manager",
            "Packaging technologist",
            "Paediatric nurse",
            "Paramedic",
            "Patent attorney",
            "Patent examiner",
            "Pension scheme manager",
            "Personal assistant",
            "Petroleum engineer",
            "Pharmacist",
            "Pharmacologist",
            "Pharmacovigilance officer",
            "Photographer",
            "Physiotherapist",
            "Picture researcher",
            "Planning and development surveyor",
            "Planning technician",
            "Plant breeder",
            "Police officer",
            "Political party agent",
            "Political party research officer",
            "Practice nurse",
            "Press photographer",
            "Press sub-editor",
            "Prison officer",
            "Private music teacher",
            "Probation officer",
            "Product development scientist",
            "Production manager",
            "Programme researcher",
            "Project manager",
            "Psychologist (clinical)",
            "Psychologist (educational)",
            "Psychotherapist",
            "Public affairs consultant (lobbyist)",
            "Public affairs consultant (research)",
            "Public house manager",
            "Public librarian",
            "Public relations (PR) officer",
            "QA analyst",
            "Quality assurance manager",
            "Quantity surveyor",
            "Records manager",
            "Recruitment consultant",
            "Recycling officer",
            "Regulatory affairs officer",
            "Research chemist",
            "Research scientist",
            "Restaurant manager",
            "Retail banker",
            "Retail buyer",
            "Retail manager",
            "Retail merchandiser",
            "Retail pharmacist",
            "Sales executive",
            "Sales manager",
            "Scene of crime officer",
            "Secretary",
            "Seismic interpreter",
            "Site engineer",
            "Site manager",
            "Social researcher",
            "Social worker",
            "Software developer",
            "Soil scientist",
            "Solicitor",
            "Speech and language therapist",
            "Sports coach",
            "Sports development officer",
            "Sports therapist",
            "Statistician",
            "Stockbroker",
            "Structural engineer",
            "Systems analyst",
            "Systems developer",
            "Tax inspector",
            "Teacher (nursery/early years)",
            "Teacher (primary)",
            "Teacher (secondary)",
            "Teacher (special educational needs)",
            "Teaching/classroom assistant",
            "Technical author",
            "Technical sales engineer",
            "TEFL/TESL teacher",
            "Television production assistant",
            "Test automation developer",
            "Tour operator",
            "Tourism officer",
            "Tourist information manager",
            "Town and country planner",
            "Toxicologist",
            "Trade union research officer",
            "Trader",
            "Trading standards officer",
            "Training and development officer",
            "Translator",
            "Transportation planner",
            "Travel agent",
            "TV/film/theatre set designer",
            "UX designer",
            "Validation engineer",
            "Veterinary nurse",
            "Veterinary surgeon",
            "Video game designer",
            "Video game developer",
            "Volunteer work organiser",
            "Warehouse manager",
            "Waste disposal officer",
            "Water conservation officer",
            "Water engineer",
            "Web designer",
            "Web developer",
            "Welfare rights adviser",
            "Writer",
            "Youth worker"
        ];

        foreach ($arr as $key => $value) {

            $professions = Profession::where('name',$value)->count();
            // dd($professions);

            if ($professions == 0) 
            {
                $profession = Profession::create([
                    'name' => $value,
                    'slug' => Str::slug(str_replace('/', ' ', $value), '-'),
                    'image' => '',
                ]);
            } 
        }
         

        dd('done');
    }

    public function cronTest(){
    
        \Log::info("Cron is working fine!");

        $contests = Contest::where('status', 1)->where('end_date','<',now()->format('Y-m-d'))->get();
        $participants = collect();

        foreach ($contests as $key => $contest) {
            
            $feature_ids = DB::table('votes')
                ->select(DB::raw('count(*) as vote_count, participant_id'))
                ->where('contest_id', '=', $contest->id)
                ->groupBy('participant_id')
                ->orderByDesc('vote_count')
                ->pluck('participant_id')
                ->take(1)->toArray();

            if (count($feature_ids) > 0) {
                $tempStr = implode(',', $feature_ids);
                $featured = Participant::whereIn('id', $feature_ids)
                            ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
                            ->take(1)
                            ->first();

                $participants->push($featured);
            }
        }  

        foreach ($participants as $key => $participant) {

            $a_date = $participant->contest->end_date;
            $sd = now()->format('Y-m-d 00:00:00');
            $ed = date("Y-m-t 00:00:10", strtotime(now()->format('Y-m-d 00:00:00')));

            \Log::info('Participant_id:'.$participant->id);

            try 
            {
                Mail::to($participant->user->email)->send(new WinnerMail($participant->user,$participant,$participant->contest));

                if ($participant->contest->type_id == "1") 
                {
                    $a_date = $participant->contest->end_date;

                    $contest = $participant->contest->update([
                        // 'status' => 2,
                        'start_date' => now()->format('Y-m-d 00:00:00'),
                        'end_date' => date("Y-m-t 00:00:10", strtotime(now()->format('Y-m-d 00:00:00'))),
                        'amount' => 0
                    ]);
                }
                elseif ($participant->contest->type_id == "2") 
                {
                    $b_date = $participant->contest->end_date;

                    $contest = $participant->contest->update([
                        // 'status' => 2,
                        'start_date' => now()->format('Y-m-d 00:00:00'),
                        'end_date' => date("Y-12-31 00:00:10", strtotime(now()->format('Y-m-d 00:00:00'))),
                        'amount' => 0
                    ]);
                }
                elseif ($participant->contest->type_id == "3") 
                {
                    $c_date = $participant->contest->end_date;

                    $contest = $participant->contest->update([
                        // 'status' => 2,
                        'start_date' => now()->format('Y-m-d 00:00:00'),
                        'end_date' => date("Y-m-t 00:00:10", strtotime(now()->format('Y-m-d 00:00:00'))),
                        'amount' => 0
                    ]);
                }
                
                $user = $participant->user;
                $user->amount += $participant->amount;
                $user->save();

                \Log::info('Winner Email Done:'.$participant->id);
            
            }
            catch (\Throwable $th) {
                
            }
        }

        foreach ($contests as $key => $contest) {

            foreach ($contest->participants as $key => $participant) {

                $participant = $participant->update([
                    'status' => 2,
                ]);
            }
        }

        Log::info('Job completed');
    }
}
