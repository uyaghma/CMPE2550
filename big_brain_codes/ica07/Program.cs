using System.Reflection.Metadata.Ecma335;
using System.Text.RegularExpressions;

namespace ica07
{
    public class Program
    {
        public static void Main(string[] args)
        {
            var builder = WebApplication.CreateBuilder(args);
            builder.Services.AddControllers();
            var app = builder.Build();
            app.UseCors(x => x.AllowAnyMethod().AllowAnyHeader().SetIsOriginAllowed(origin => true));

            Random dice = new Random();
            int numMoves = 0;
            string p2name = "";
            string p1name = "";
            string winner = "";
            string status = "";
            int position = 45;
            bool gameStarted = false;
            bool quit = false;

            app.MapPost("/newgame", (UserInfo players) =>
            {
                p1name = players.p1name.Trim();
                p2name = players.p2name;
                quit = false;
                gameStarted = true;
                numMoves = 0;
                winner = "";
                status = "";
                position = 45;

                return new
                {
                    start = gameStarted,
                    winner = winner,
                    maxmoves = numMoves,
                    status = $"<h3>Game Start</h3>",
                    pos = position
                };
            });

            app.MapPost("/movepiece", (Moves pos) =>
            {
                position = pos.pos;
                numMoves = pos.maxmoves;
                status = pos.status;
                winner = pos.winner;
                gameStarted = pos.start;
                
                if (gameStarted && !quit)
                {
                    numMoves++;
                    if (numMoves > 30)
                    {
                        status = "<h3>Draw -> No Winner!</h3>";
                    }
                    else
                    {
                        int p1 = dice.Next(-7, -1);
                        int p2 = dice.Next(1, 7);
                        int move = p1 + p2;
                        string dir = move < 0 ? $"{Math.Abs(move)} moves to the left" : move == 0 ? "no moves made" : $"{Math.Abs(move)} moves to the right";
                        position += move;

                        if (position > 85)
                        {
                            winner = p2name;
                        }
                        else if (position < 5)
                        {
                            winner = p1name;
                        }

                        if (winner != "")
                        {
                            status = $"<h3>{winner} wins!</h3>";
                            gameStarted = false;
                        }
                        else
                        {
                            status = $"<h3>{p1name} rolled {Math.Abs(p1)}, {p2name} rolled {Math.Abs(p2)} -> {dir}</h3>";
                        }
                    }
                }
                else
                {
                    status = $"<h3>New game must be started before pulling!</h3>";
                }
                return new
                {
                    pos = position,
                    status = status,
                    winner = winner,
                    maxmoves = numMoves,
                    start = gameStarted
                };
            });

            app.MapPost("/quitgame", () =>
            {
                gameStarted = false;
                quit = true;
                return new
                {
                    status = "<h3>You quit! Press 'New Game' to start again.</h3>",
                    start = gameStarted,
                    quit = quit
                };
            });

            app.Run();
        }
        record Moves (int pos, int maxmoves, string status, string winner, bool start);
        record UserInfo (string p1name, string p2name);
    }
}
