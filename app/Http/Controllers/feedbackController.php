<?php

namespace App\Http\Controllers;

use App\Http\Resources\Feedback as ResourcesFeedback;
use App\Models\feedBack;
use App\Notifications\FeedbackResponseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class feedbackController extends Controller
{

    //Recupere les feedback depuis la bas de donne avec de Ressources

    public function getFeedback (Request $request) {

      $getAll = ResourcesFeedback::collection(feedBack::all());
            

      return $getAll;
    }
    //Creation de feeback dans la base de Donner

    public function AddFeed (Request $request) {
      // Étape 1 : Validation des données envoyées par l'utilisateur
    // Ici, nous définissons les règles pour chaque champ du formulaire

        $validate = Validator::make($request->all(), [
            // Regle de Validation
            'name' => "required|string",  
            'email' => "required|email" ,
            'product_service' => "required|string",  
            'rating' => 'required|integer|min:1|max:5',  
            'comment' => "required|string",  
            //  'product_id' => "required|exists:products,id"
        ]);

      // Étape 2 : Vérification des erreurs de validation

        if ($validate ->fails()) {
            // Si la validation échoue, on renvoie une réponse JSON avec les erreurs
            return response()->json(['errors' => $validate->errors()], 400);
            // Code HTTP 400 : Mauvaise requête
        }

         // Étape 3 : Enregistrement des données dans la base de données
         // Utilisation du modèle Feedback pour créer une nouvelle entrée
        $createFeddback = feedBack::create([
            'name' => $request->input('name'),  
            'email' => $request->input('email'),
            'product_service' => $request->input('product_service'),  
            'rating' => $request->input('rating'),  
            // 'product_id' => $request->input('product_id'),  
            'comment' =>$request->input('comment'),  
            // 'response' => $request->input('response'),  // Désactivé pour l'instant
        ],201);


      // Étape 4 : Réponse en cas de succès
      // On renvoie une réponse JSON avec les données du feedback créé

     return response()->json([
        'feedback' => $createFeddback,  // Les données sauvegardées
        'message' => 'Your feedback was sent successfully.',  // Message de confirmation
     ]);


    }



    // Suppression de Feedback 

    public function deleteFeback (int $id) {
       
        $feedBack = feedBack::find($id);
       
        $feedBack->delete();


        return response()->json([
            "message" =>  "this Cette "
        ]);
    }

      
    public function showFedback (int $id) {
       
        $feedback = feedBack::findOrFail($id);
        
        // Retourner la ressource formatée
        return response()->json(new ResourcesFeedback($feedback), 200);
    }

    public function RespondToFeedback(Request $request,int $id) {
        // Étape 1 : Validation des données envoyées par l'admin
        $validate = Validator::make($request->all(), [
            'response' => 'required|string',  // Validation du message de réponse de l'admin
        ]);
    
        // Étape 2 : Vérification des erreurs de validation
        if ($validate->fails()) {
            // Si la validation échoue, on renvoie une réponse JSON avec les erreurs
            return response()->json(['errors' => $validate->errors()], 400);
        }
    
        // Étape 3 : Récupérer le feedback spécifique par ID
        $feedback = feedBack::find($id);
        // dd([$feedback, $email])  ;  
        if (!$feedback) {
            // Si le feedback n'existe pas, on renvoie une erreur
            return response()->json(['error' => 'Feedback not found'], 404);
        }
        $email = $feedback->email;

    
        // Étape 4 : Mise à jour du feedback
        $feedback->response = $request->input('response');  // Enregistrer la réponse de l'admin
        $feedback->status = 'répondu';  // Mettre à jour le statut du feedback pour 'responded'
    
        // Sauvegarder les modifications
        $feedback->save();


         // Envoi de la notification à l'utilisateur qui a soumis le feedback
            $feedback->notify(new FeedbackResponseNotification($feedback->response));

      
   
        // Étape 5 : Réponse en cas de succès
        return response()->json([
            // 'feedback' => $feedback,  // Les données mises à jour du feedback
            'message' => 'The feedback was responded successfully.'  // Message de confirmation
        ]);
    }

    public function getFeedbackStatistics()
{
    // Moyenne des évaluations par produit et nombre total de feedbacks
    $averageRating = Feedback::selectRaw('AVG(rating) as average_rating')
    ->selectRaw('COUNT(*) as total_feedbacks')
    ->first();

return response()->json($averageRating);
}
    
public function getRatingDistribution()
{
    // Distribution des notes
    $ratingDistribution = feedback::select('rating', DB::raw('COUNT(*) as count'))
        ->groupBy('rating')
        ->orderBy('rating')
        ->get();

    return response()->json($ratingDistribution);
}







}
